<?php

namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\FreteException;
use EscapeWork\Frete\Collection;
use GuzzleHttp\Client;
use Exception, InvalidArgumentException;
use SoapClient;

class Rastreamento extends BaseCorreios
{
    /**
     * Result
     * @var EscapeWork\Frete\Correios\RastreamentoResult
     */
    protected $result;

    /**
     * Timeout da requisição ao WS dos Correios
     * @var int
     */
    protected $requestTimeout = 1;

    /**
     * Data
     * @var array
     */
    protected $data = [
        'Usuario'   => '',
        'Senha'     => '',
        'Tipo'      => 'L',
        'Resultado' => 'U',
        'Objetos'   => [],
    ];

    public function __construct(RastreamentoResult $result = null)
    {
        if (! $this->result = $result) {
            $this->result = new RastreamentoResult;
        }
    }

    public function setUsuario($usuario)
    {
        $this->data['Usuario'] = $usuario;
        return $this;
    }

    public function setSenha($senha)
    {
        $this->data['Senha'] = $senha;
        return $this;
    }

    public function setTipo($tipo)
    {
        if (! in_array($tipo, ['L', 'F'])) {
            throw new InvalidArgumentException('Apenas os valores L ou F são suportados para o tipo');
        }

        $this->data['Tipo'] = $tipo;
        return $this;
    }

    public function setResultado($resultado)
    {
        if (! in_array($resultado, ['T', 'U'])) {
            throw new InvalidArgumentException('Apenas os valores T ou U são suportados para o tipo');
        }

        $this->data['Resultado'] = $resultado;
        return $this;
    }

    public function setObjetos($objetos)
    {
        $this->data['Objetos'] = (array) $objetos;
        return $this;
    }

    /**
     * Get request timeout
     * @return int
     */
    public function getRequestTimeout()
    {
        return $this->requestTimeout;
    }

    /**
     * Set request timeout
     * @param int $requestTimeout
     * @return Rastreamento
     */
    public function setRequestTimeout($requestTimeout)
    {
        $this->requestTimeout = $requestTimeout;
        return $this;
    }

    public function track()
    {
        ini_set('default_socket_timeout', $this->requestTimeout);

        try {
            $client   = new SoapClient(__DIR__.'/../../resources/Rastro.wsdl');
            $response = $client->buscaEventos($this->getData());

            return $this->result($response->return);
        } catch (Exception $e) {
            throw new FreteException('Houve um erro ao buscar os dados. Verifique se todos os dados estão corretos', 1);
        }
    }

    protected function result($data)
    {
        if (! isset($data->error)) {
            if (isset($data->objeto->numero)) {
                if (isset($data->objeto->erro)) {
                    throw new FreteException($data->objeto->erro, 0);
                }
                $this->result->fill($data->objeto);

                return $this->result;
            } else {
                return $this->makeCollection($data);
            }
        } else {
            throw new FreteException($data->error, 0);
        }
    }

    protected function getData()
    {
        return array(
            'usuario'   => $this->data['Usuario'],
            'senha'     => $this->data['Senha'],
            'tipo'      => $this->data['Tipo'],
            'resultado' => $this->data['Resultado'],
            'lingua'    => '101',
            'objetos'   => implode('', $this->data['Objetos']),
        );
    }

    protected function makeCollection($data)
    {
        $objects = new Collection;

        foreach ($data->objeto as $objeto) {
            $result = new RastreamentoResult();
            $result->fill($objeto);

            $objects[] = $result;
        }

        return $objects;
    }
}
