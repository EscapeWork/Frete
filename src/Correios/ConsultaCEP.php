<?php

namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\FreteException;
use EscapeWork\Frete\Collection;
use GuzzleHttp\Client;
use Exception, InvalidArgumentException;
use SoapClient;

class ConsultaCEP extends BaseCorreios
{
    /**
     * Guzzle client
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Result
     * @var EscapeWork\Frete\Correios\ConsultaCEPResult
     */
    protected $result;

    /**
     * CEP
     * @var int
     */
    protected $cep;

    /**
     * Data
     * @var array
     */
    protected $data = [
        'cep' => '',
    ];

    /**
     * Tipo de retorno do conteúdo
     *
     * Tipos disponíveis (xml)
     */
    private $retorno = 'xml';

    public function __construct(Client $client = null, ConsultaCEPResult $result = null)
    {
        if (! $this->client = $client) {
            $this->client = new Client;
        }

        if (! $this->result = $result) {
            $this->result = new ConsultaCEPResult;
        }
    }

    public function setCep($cep)
    {
        $this->data['cep'] = $cep;
        return $this;
    }

    public function find()
    {
        try {
            $client   = new SoapClient(__DIR__.'/../../resources/AtendeCliente.wsdl');
            $response = $client->consultaCEP($this->data);

            return $this->result($response->return);
        }
        catch (\Exception $e) {
            $exception = new FreteException($e->getMessage());
            throw $exception;
        }
    }

    protected function result($data)
    {
        $this->result->fill((array) $data);
        return $this->result;
    }

    protected function hasError($data)
    {
        if (isset($data['cServico']['Erro'])) {
            return ! in_array($data['cServico']['Erro'], $this->successfulCodes);
        }

        return ! in_array($data['cServico'][0]['Erro'], $this->successfulCodes);
    }

    protected function getErrorCode($data)
    {
        if (isset($data['cServico']['Erro'])) {
            return is_array($data['cServico']['Erro']) ? array_shift($data['cServico']['Erro']) : $data['cServico']['Erro'];
        }

        return is_array($data['cServico'][0]['Erro']) ? array_shift($data['cServico'][0]['Erro']) : $data['cServico'][0]['Erro'];
    }

    protected function getErrorMessage($data)
    {
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
            $result = new ConsultaCEPResult();
            $result->fill($precoPrazo);

            $objects[] = $result;
        }

        return $objects;
    }
}
