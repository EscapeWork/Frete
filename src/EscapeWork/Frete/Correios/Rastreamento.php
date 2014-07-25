<?php namespace EscapeWork\Frete\Correios;

use GuzzleHttp\Client;

class Rastreamento
{

    /**
     * Variaveis de configuração
     */
    protected
        $usuario,
        $senha,
        $tipo,
        $resultado,
        $objetos;

    public function __construct()
    {
        $client = new Client();
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    public function setObjetos($objetos)
    {
        $this->objetos = $objetos;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getResultado()
    {
        return $this->resultado;
    }

    public function getObjetos()
    {
        return $this->objetos;
    }

    public function execute()
    {
        $this->client->post(DATA::URL_RASTREAMENTO, [
            'body' => $this->getParameters()
        ]);
    }

    public function getParameters()
    {
        return [
            'Usuario'   => $this->getUsuario(),
            'Senha'     => $this->getSenha(),
            'Tipo'      => $this->getTipo(),
            'Resultado' => $this->getResultado(),
            'Objetos'   => $this->getObjetos(),
        ];
    }
}
