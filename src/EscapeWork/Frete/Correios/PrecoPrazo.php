<?php namespace EscapeWork\Frete\Correios;

use InvalidArgumentException;

class PrecoPrazo
{

    /**
     * Códigos de erro aceitos
     */
    protected $successfulCodes = ['0', '010'];

    /**
     * Formatos validos
     */
    protected $formatosValidos = [1, 2, 3];

    /**
     * Data
     * @var array
     */
    protected $data = [
        'nCdEmpresa'          => '',            # Seu código administrativo junto à ECT
        'sDsSenha'            => '',            # Senha para acesso ao serviço
        'nCdServico'          => '40010,41106', # Código do serviço - Ver classe CodigoServico
        'sCepOrigem'          => '',            # CEP de Origem sem hífen.Exemplo: 05311900
        'sCepDestino'         => '',            # CEP de Destino sem hífen
        'nVlPeso'             => '',            # Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas. Se o formato for Envelope, o valor máximo permitido será 1 kg;
        'nCdFormato'          => 1,             # Formato da encomenda (incluindo embalagem). Valores possíveis: 1, 2 ou 3 1 – Formato caixa/pacote | 2 – Formato rolo/prisma | 3 - Envelope
        'nVlComprimento'      => '',            # Comprimento da encomenda (incluindo embalagem), em centímetros.
        'nVlAltura'           => '',            # Altura da encomenda (incluindo embalagem), em centímetros. Se o formato for envelope, informar zero (0).
        'nVlLargura'          => '',            # Largura da encomenda (incluindo embalagem), em centímetros.
        'nVlDiametro'         => '',            # Diâmetro da encomenda (incluindo embalagem), em centímetros.
        'sCdMaoPropria'       => 'N',           # S ou N; Indica se a encomenda será entregue com o serviço adicional mão própria;
        'nVlValorDeclarado'   => 0,             # Valor em Reais; Indica se a encomenda será entregue com o serviço adicional valor declarado;
        'sCdAvisoRecebimento' => 'N';           # S ou N; Indica se a encomenda será entregue com o serviço adicional aviso de recebimento.
    ];

    /**
     * Tipo de retorno do conteúdo
     *
     * Tipos disponíveis (xml)
     */
    private $retorno = 'xml';

    public function setCodigoEmpresa($nCdEmpresa)
    {
        $this->data['nCdEmpresa'] = $nCdEmpresa;
        return $this;
    }

    public function setSenha($sDsSenha)
    {
        $this->data['sDsSenha'] = $sDsSenha;
        return $this;
    }

    public function setCodigoServico($nCdServico)
    {
        $this->data['nCdServico'] = $nCdServico;
        return $this;
    }

    public function setCepOrigem($sCepOrigem)
    {
        $this->data['sCepOrigem'] = $sCepOrigem;
        return $this;
    }

    public function setCepDestino($sCepDestino)
    {
        $this->data['sCepDestino'] = $sCepDestino;
        return $this;
    }

    public function setPeso($nVlPeso)
    {
        $this->data['nVlPeso'] = $nVlPeso;
        return $this;
    }

    public function setFormato($nCdFormato)
    {
        if (! in_array($nCdFormato, $this->formatosValidos)) {
            throw new InvalidArgumentException('Apenas os valores 1, 2 ou 3 São suportados');
        }

        $this->data['nCdFormato'] = $nCdFormato;
        return $this;
    }

    public function setComprimento($nVlComprimento)
    {
        $this->data['nVlComprimento'] = $nVlComprimento;
        return $this;
    }

    public function setAltura($nVlAltura)
    {
        $this->data['nVlAltura'] = $nVlAltura;
        return $this;
    }

    public function setLargura($nVlLargura)
    {
        $this->data['nVlLargura'] = $nVlLargura;
        return $this;
    }

    public function setDiametro($nVlDiametro)
    {
        $this->data['nVlDiametro'] = $nVlDiametro;
        return $this;
    }

    public function setMaoPropria($sCdMaoPropria)
    {
        $validTypes = array('S', 'N');

        if (! in_array($sCdMaoPropria, $validTypes)) {
            throw new InvalidArgumentException('Apenas os valores S ou N São suportados');
        }

        $this->data['sCdMaoPropria'] = $sCdMaoPropria;
        return $this;
    }

    public function setValorDeclarado($nVlValorDeclarado)
    {
        $this->data['nVlValorDeclarado'] = $nVlValorDeclarado;
        return $this;
    }

    public function setAvisoRecebimento($sCdAvisoRecebimento)
    {
        $validTypes = array('S', 'N');
        if (! in_array($sCdAvisoRecebimento, $validTypes)) {
            throw new InvalidArgumentException('Apenas os valores S ou N São suportados');
        }

        $this->data['sCdAvisoRecebimento'] = $sCdAvisoRecebimento;
        return $this;
    }

    /**
     * Fazendo a requisição e recebendo o retorno
     */
    public function calcular()
    {
        $url       = $this->buildUrl();
        $this->xml = @simplexml_load_file($url);

        if (! is_object($this->xml)) {
            throw new FreteException("Houve um erro ao buscar os dados. Verifique se todos os dados estão corretos");
        }

        return $this->result($this->xml);
    }

    private function buildUrl()
    {
        return Data::URL_PRECO_PRAZO . '?' . $this->getParameters();
    }

    public function getParameters()
    {
        $data = array_merge(
            $this->data,
            ['StrRetorno' => $this->retorno]
        );

        return http_build_query($data, '', '&');
    }

    protected function result($data)
    {
        $result = new Result;

        if (in_array($data->cServico->Erro, $this->successfulCodes)) {
            $result->setSuccessful(true);
        } else {
            $result->setSuccessful(false);
        }

        $result->fill([
            'Codigo'                => $data->cServico->Codigo,
            'Valor'                 => $data->cServico->Valor,
            'PrazoEntrega'          => $data->cServico->PrazoEntrega,
            'ValorMaoPropria'       => $data->cServico->ValorMaoPropria,
            'ValorAvisoRecebimento' => $data->cServico->ValorAvisoRecebimento,
            'ValorValorDeclarado'   => $data->cServico->ValorValorDeclarado,
            'EntregaDomiciliar'     => $data->cServico->EntregaDomiciliar,
            'EntregaSabado'         => $data->cServico->EntregaSabado,
            'Erro'                  => $data->cServico->Erro,
            'MsgErro'               => $data->cServico->MsgErro,
        ]);

        return $result;
    }
}
