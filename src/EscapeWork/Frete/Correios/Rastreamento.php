<?php namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\Result;
use EscapeWork\Frete\FreteException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ParseException;
use InvalidArgumentException;

class Rastreamento
{

    /**
     * Guzzle client
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Result
     * @var EscapeWork\Frete\Result
     */
    protected $result;

    /**
     * Data
     * @var array
     */
    protected $data = array(
        'Usuario'   => '',
        'Senha'     => '',
        'Tipo'      => 'L',
        'Resultado' => 'T',
        'Objetos'   => '',
    );

    public function __construct(Client $client, Result $result)
    {
        $this->client = $client;
        $this->result = $result;
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
        if (! in_array($tipo, array('L', 'F'))) {
            throw new InvalidArgumentException('Apenas os valores L ou F são suportados para o tipo');
        }

        $this->data['Tipo'] = $tipo;
        return $this;
    }

    public function setResultado($resultado)
    {
        if (! in_array($tipo, array('T', 'U'))) {
            throw new InvalidArgumentException('Apenas os valores T ou U são suportados para o tipo');
        }

        $this->data['Resultado'] = $resultado;
        return $this;
    }

    public function setObjetos($objetos)
    {
        if (strlen($objetos) % 13 !== 0) {
            throw new InvalidArgumentException('O valor de cada objeto precisa ter 13 caracteres');
        }

        $this->data['Objetos'] = $objetos;
        return $this;
    }

    public function track()
    {
        $result = $this->client->post(DATA::URL_RASTREAMENTO, [
            'body' => $this->data
        ]);

        try {
            $xml = $result->xml();

            return $this->result($xml);
        } catch (ParseException $e) {
            throw new FreteException('Houve um erro ao buscar os dados. Verifique se todos os dados estão corretos');
        }
    }

    protected function result($data)
    {
        $this->result->setSuccessful(true);
        $this->result->fill([
            'Versao'        => $data->Versao,
            'Qtd'           => $data->Qtd,
            'TipoPesquisa'  => $data->TipoPesquisa,
            'TipoResultado' => $data->TipoResultado,
            'Objeto'        => $data->Objeto,
            'Evento'        => (array) $data->Evento,
        ]);

        return $this->result;
    }
}
