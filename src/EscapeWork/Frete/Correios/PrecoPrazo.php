<?php namespace EscapeWork\Frete\Correios;

use InvalidArgumentException;

class PrecoPrazo
{


    /**
     * Variáveis para serem enviadas para o webservice
     */
    public
        $nCdEmpresa,                # Seu código administrativo junto à ECT
        $sDsSenha,                  # Senha para acesso ao serviço
        $nCdServico,                # Código do serviço - Ver classe CodigoServico
        $sCepOrigem,                # CEP de Origem sem hífen.Exemplo: 05311900
        $sCepDestino,               # CEP de Destino sem hífen
        $nVlPeso,                   # Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas. Se o formato for Envelope, o valor máximo permitido será 1 kg;
        $nCdFormato = 1,            # Formato da encomenda (incluindo embalagem). Valores possíveis: 1, 2 ou 3 1 – Formato caixa/pacote | 2 – Formato rolo/prisma | 3 - Envelope
        $nVlComprimento,            # Comprimento da encomenda (incluindo embalagem), em centímetros.
        $nVlAltura,                 # Altura da encomenda (incluindo embalagem), em centímetros. Se o formato for envelope, informar zero (0).
        $nVlLargura,                # Largura da encomenda (incluindo embalagem), em centímetros.
        $nVlDiametro,               # Diâmetro da encomenda (incluindo embalagem), em centímetros.
        $sCdMaoPropria = 'N',       # S ou N; Indica se a encomenda será entregue com o serviço adicional mão própria;
        $nVlValorDeclarado = 0,     # Valor em Reais; Indica se a encomenda será entregue com o serviço adicional valor declarado;
        $sCdAvisoRecebimento = 'N'; # S ou N; Indica se a encomenda será entregue com o serviço adicional aviso de recebimento.

    /**
     * Tipo de retorno do conteúdo
     *
     * Tipos disponíveis (xml)
     */
    private $retorno = 'xml';

    public function setCodigoEmpresa($nCdEmpresa)
    {
        $this->nCdEmpresa = $nCdEmpresa;

        return $this;
    }

    public function setSenha($sDsSenha)
    {
        $this->sDsSenha = $sDsSenha;

        return $this;
    }

    public function setCodigoServico($nCdServico)
    {
        if (! in_array($nCdServico, Data::$codigos)) {
            throw new InvalidArgumentException('Código não suportado');
        }

        $this->nCdServico = $nCdServico;

        return $this;
    }

    public function setCepOrigem($sCepOrigem)
    {
        $this->sCepOrigem = $sCepOrigem;

        return $this;
    }

    public function setCepDestino($sCepDestino)
    {
        $this->sCepDestino = $sCepDestino;

        return $this;
    }

    public function setPeso($nVlPeso)
    {
        $this->nVlPeso = $nVlPeso;

        return $this;
    }

    public function setFormato($nCdFormato)
    {
        $validTypes = array(1, 2, 3);
        if (! in_array($nCdFormato, $validTypes)) {
            throw new InvalidArgumentException('Apenas os valores 1, 2 ou 3 São suportados');
        }
        $this->nCdFormato = $nCdFormato;

        return $this;
    }

    public function setComprimento($nVlComprimento)
    {
        $this->nVlComprimento = $nVlComprimento;

        return $this;
    }

    public function setAltura($nVlAltura)
    {
        $this->nVlAltura = $nVlAltura;

        return $this;
    }

    public function setLargura($nVlLargura)
    {
        $this->nVlLargura = $nVlLargura;

        return $this;
    }

    public function setDiametro($nVlDiametro)
    {
        $this->nVlDiametro = $nVlDiametro;

        return $this;
    }

    public function setMaoPropria($sCdMaoPropria)
    {
        $validTypes = array('S', 'N');
        if (! in_array($sCdMaoPropria, $validTypes)) {
            throw new InvalidArgumentException('Apenas os valores S ou N São suportados');
        }

        $this->sCdMaoPropria = $sCdMaoPropria;

        return $this;
    }

    public function setValorDeclarado($nVlValorDeclarado)
    {
        $this->nVlValorDeclarado = $nVlValorDeclarado;

        return $this;
    }

    public function setAvisoRecebimento($sCdAvisoRecebimento)
    {
        $validTypes = array('S', 'N');
        if (! in_array($sCdAvisoRecebimento, $validTypes)) {
            throw new InvalidArgumentException('Apenas os valores S ou N São suportados');
        }

        $this->sCdAvisoRecebimento = $sCdAvisoRecebimento;

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
    }

    private function buildUrl()
    {
        return Data::URL_PRECO_PRAZO . '?' . $this->getParameters();
    }

    public function getParameters()
    {
        $data = array(
            'nCdEmpresa'          => $this->nCdEmpresa,
            'sDsSenha'            => $this->sDsSenha,
            'nCdServico'          => $this->nCdServico,
            'sCepOrigem'          => $this->sCepOrigem,
            'sCepDestino'         => $this->sCepDestino,
            'nVlPeso'             => $this->nVlPeso,
            'nCdFormato'          => $this->nCdFormato,
            'nVlComprimento'      => $this->nVlComprimento,
            'nVlAltura'           => $this->nVlAltura,
            'nVlLargura'          => $this->nVlLargura,
            'nVlDiametro'         => $this->nVlDiametro,
            'sCdMaoPropria'       => $this->sCdMaoPropria,
            'nVlValorDeclarado'   => $this->nVlValorDeclarado,
            'sCdAvisoRecebimento' => $this->sCdAvisoRecebimento,
            'StrRetorno'          => $this->retorno,
        );

        return http_build_query($data, '', '&');
    }

    /**
     * Retornando código do XML
     *
     * @access public
     * @return string
     */
    public function getCodigoXml()
    {
        return (string) $this->xml->cServico->Codigo;
    }

    /**
     * Retornando valor do frete
     *
     * @access public
     * @return string
     */
    public function getValor()
    {
        return (string) $this->xml->cServico->Valor;
    }

    /**
     * Retornando Prazo de Entrega
     *
     * @access public
     * @return string
     */
    public function getPrazoEntrega()
    {
        return (string) $this->xml->cServico->PrazoEntrega;
    }

    /**
     * Retornando Valor do serviço Mao Propria.
     * Será 0 caso tenha rejeitado este serviço
     *
     * @access public
     * @return string
     */
    public function getValorMaoPropria()
    {
        return (string) $this->xml->cServico->ValorMaoPropria;
    }

    /**
     * Retornando Valor do Aviso de recebimento
     *
     * @access public
     * @return string
     */
    public function getValorAvisoRecebimento()
    {
        return (string) $this->xml->cServico->ValorAvisoRecebimento;
    }

    /**
     * Retornando Valor do Valor Declarado
     *
     * @access public
     * @return string
     */
    public function getValorValorDeclarado()
    {
        return (string) $this->xml->cServico->ValorValorDeclarado;
    }

    /**
     * Retornando se entrega será domiciliar
     * S para Sim ou N para Não
     *
     * @access public
     * @return string
     */
    public function getEntregaDomiciliar()
    {
        return (string) $this->xml->cServico->EntregaDomiciliar;
    }

    /**
     * Retornando Se será feita entrega no sábado
     * S para Sim ou N para Não
     *
     * @access public
     * @return string
     */
    public function getEntregaSabado()
    {
        return (string) $this->xml->cServico->EntregaSabado;
    }

    /**
     * Retornando Se será feita entrega no sábado
     * S para Sim ou N para Não
     *
     * @access public
     * @return string
     */
    public function getErro()
    {
        return (string) $this->xml->cServico->Erro;
    }

    /**
     * Retornando array com erros
     *
     * @access public
     * @return string
     */
    public function getMsgErro()
    {
        return (string) $this->xml->cServico->MsgErro;
    }
}
