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
        $this->data['cep'] = preg_replace("/[^0-9]/", "", $cep);
        return $this;
    }

    public function find()
    {
        try {
            // $client   = new SoapClient(Data::URL_CONSULTA_CEP);
            $client   = new SoapClient(__DIR__.'/../../resources/AtendeCliente.wsdl');
            $response = $client->consultaCEP($this->data);

            $this->result->fill((array) $response->return);
            return $this->result;
        }
        catch (\Exception $e) {
            $exception = new FreteException($e->getMessage());
            throw $exception;
        }
    }

    public static function search($cep)
    {
        return (new ConsultaCEP)->setCep($cep)
                                ->find();
    }
}
