<?php namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\FreteException;
use EscapeWork\Frete\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class PrecoPrazo extends BaseCorreios
{

    /**
     * Guzzle client
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Result
     * @var EscapeWork\Frete\Correios\PrecoPrazoResult
     */
    protected $result;

    /**
     * Códigos de erro aceitos
     */
    protected $successfulCodes = array('0', '010');

    /**
     * Formatos validos
     * @var array
     */
    protected $formatosValidos = array(1, 2, 3);

    /**
     * Data
     * @var array
     */
    protected $data = array(
        'nCdEmpresa'          => '',                 # Seu código administrativo junto à ECT
        'sDsSenha'            => '',                 # Senha para acesso ao serviço
        'nCdServico'          => ['40010', '41106'], # Código do serviço - Ver classe EscapeWork\Frete\Correios\Data
        'sCepOrigem'          => '',                 # CEP de Origem sem hífen.Exemplo: 05311900
        'sCepDestino'         => '',                 # CEP de Destino sem hífen
        'nVlPeso'             => '',                 # Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas. Se o formato for Envelope, o valor máximo permitido será 1 kg;
        'nCdFormato'          => 1,                  # Formato da encomenda (incluindo embalagem). Valores possíveis: 1, 2 ou 3 1 – Formato caixa/pacote | 2 – Formato rolo/prisma | 3 - Envelope
        'nVlComprimento'      => '',                 # Comprimento da encomenda (incluindo embalagem), em centímetros.
        'nVlAltura'           => '',                 # Altura da encomenda (incluindo embalagem), em centímetros. Se o formato for envelope, informar zero (0).
        'nVlLargura'          => '',                 # Largura da encomenda (incluindo embalagem), em centímetros.
        'nVlDiametro'         => '',                 # Diâmetro da encomenda (incluindo embalagem), em centímetros.
        'sCdMaoPropria'       => 'N',                # S ou N; Indica se a encomenda será entregue com o serviço adicional mão própria;
        'nVlValorDeclarado'   => 0,                  # Valor em Reais; Indica se a encomenda será entregue com o serviço adicional valor declarado;
        'sCdAvisoRecebimento' => 'N',                # S ou N; Indica se a encomenda será entregue com o serviço adicional aviso de recebimento.
    );

    /**
     * Tipo de retorno do conteúdo
     *
     * Tipos disponíveis (xml)
     */
    private $retorno = 'xml';

    public function __construct(Client $client = null, PrecoPrazoResult $result = null)
    {
        if (! $this->client = $client) {
            $this->client = new Client;
        }

        if (! $this->result = $result) {
            $this->result = new PrecoPrazoResult;
        }
    }

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

    public function setCodigoServico($codigo)
    {
        $this->data['nCdServico'] = (array) $codigo;
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

    public function calculate()
    {
        $result = $this->client->get($this->buildUrl());

        try {
            $xml = $result->getBody()->getContents();

            return $this->result($xml);
        } catch (RequestException $e) {
            throw new FreteException('Houve um erro ao buscar os dados. Verifique se todos os dados estão corretos', 1);
        }
    }

    protected function buildUrl()
    {
        return Data::URL_PRECO_PRAZO . '?' . $this->getParameters();
    }

    public function getParameters()
    {
        $data = array_merge(
            $this->data,
            [
                'StrRetorno' => $this->retorno,
                'nCdServico' => implode(',', $this->data['nCdServico'])
            ]
        );

        return http_build_query($data, '', '&');
    }

    protected function result($data)
    {
        $data = $this->xmlToArray($data);

        if ($this->hasError($data)) {
            throw new FreteException($this->getErrorMessage($data), 0);
        }

        if (! $this->dataIsCollection($data)) {
            $this->result->fill($data);
            return $this->result;
        } else {
            return $this->makeCollection($data);
        }
    }

    protected function hasError($data)
    {
        if (empty($data)) {
            return true;
        }

        if (isset($data['cServico']['Erro'])) {
            return ! in_array($data['cServico']['Erro'], $this->successfulCodes);
        }

        return ! in_array($data['cServico'][0]['Erro'], $this->successfulCodes);
    }

    protected function getErrorMessage($data)
    {
        if (empty($data)) {
            throw new FreteException("Método de envio inválido");
        }

        if (isset($data['cServico']['MsgErro'])) {
            return (string) is_array($data['cServico']['MsgErro']) ? array_shift($data['cServico']['MsgErro']) : $data['cServico']['MsgErro'];
        }

        return (string) is_array($data['cServico'][0]['MsgErro']) ? array_shift($data['cServico'][0]['MsgErro']) : $data['cServico'][0]['MsgErro'];
    }

    protected function dataIsCollection($data)
    {
        return ! isset($data['cServico']['Codigo']);
    }

    protected function makeCollection($data)
    {
        $objects = new Collection;

        foreach ($data['cServico'] as $precoPrazo) {
            $result = new PrecoPrazoResult();
            $result->fill($precoPrazo);

            $objects[] = $result;
        }

        return $objects;
    }
}
