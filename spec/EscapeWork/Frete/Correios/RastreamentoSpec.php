<?php

namespace spec\EscapeWork\Frete\Correios;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EscapeWork\Frete\Result;
use EscapeWork\Frete\Correios\Data;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ParseException;

class RastreamentoSpec extends ObjectBehavior
{

    function let(Client $client, Result $result)
    {
        $this->beConstructedWith($client, $result);
    }

    function it_throw_an_exception_with_invalid_tipo(Client $client, Result $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setTipo', ['A']);
    }

    function it_throw_an_exception_with_invalid_resultado(Client $client, Result $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setTipo', ['B']);
    }

    function it_throw_an_exception_with_invalid_objetos(Client $client, Result $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setObjetos', ['123456789']);
    }

    function it_can_set_objetos_with_valid_value(Client $client, Result $result)
    {
        $this->setObjetos('SQ458226057BR')->shouldReturn($this);
    }

    function it_can_set_objetos_with_valid_two_values(Client $client, Result $result)
    {
        $this->setObjetos('SQ458226057BRSQ458226057BR')->shouldReturn($this);
    }

    function it_throw_an_exception_with_invalid_server_response(Client $client, Result $result)
    {
        $response = new FakeRastreamentoResponse(true, true);
        $data     = ['Usuario' => '', 'Senha' => '', 'Tipo' => 'L', 'Resultado' => 'T', 'Objetos' => ''];
        $client->post(Data::URL_RASTREAMENTO, ['body' => $data])->willReturn($response);

        $this->shouldThrow('EscapeWork\Frete\FreteException')->during('track');
    }
}

class FakeRastreamentoResponse
{

    protected
        $error     = false,
        $exception = false;

    public function __construct($error = false, $exception = false)
    {
        $this->error     = $error;
        $this->exception = $exception;
    }

    public function xml()
    {
        if ($this->exception) {
            throw new ParseException;
        }

        $servico = (object) [
            'Codigo'                => '',
            'Valor'                 => '',
            'PrazoEntrega'          => '',
            'ValorMaoPropria'       => '',
            'ValorAvisoRecebimento' => '',
            'ValorValorDeclarado'   => '',
            'EntregaDomiciliar'     => '',
            'EntregaSabado'         => '',
            'Erro'                  => $this->error ? '1' : '0',
            'MsgErro'               => '',
        ];

        return (object) [
            'cServico' => $servico
        ];
    }
}
