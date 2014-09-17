<?php

namespace spec\EscapeWork\Frete\Correios;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EscapeWork\Frete\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ParseException;

class PrecoPrazoSpec extends ObjectBehavior
{

    function let(Client $client, Result $result)
    {
        $this->beConstructedWith($client, $result);
    }

    function it_throw_an_exception_with_invalid_format(Client $client, Result $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setFormato', ['4']);
    }

    function it_throw_an_exception_with_invalid_mao_propria_value(Client $client, Result $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setMaoPropria', ['Y']);
    }

    function it_throw_an_exception_with_invalid_aviso_recebimento(Client $client, Result $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setAvisoRecebimento', ['Y']);
    }

    function it_can_calculate_with_successful_response(Client $client, Result $result)
    {
        $response = new FakeResponse;
        $client->get('http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&nCdServico=40010%2C41106&sCepOrigem=&sCepDestino=&nVlPeso=&nCdFormato=1&nVlComprimento=&nVlAltura=&nVlLargura=&nVlDiametro=&sCdMaoPropria=N&nVlValorDeclarado=0&sCdAvisoRecebimento=N&StrRetorno=xml')->willReturn($response);

        # result
        $result->setSuccessful(true)->shouldBeCalled();
        $result->fill(Argument::any())->shouldBeCalled();

        $this->calculate()->shouldBeAnInstanceOf('EscapeWork\Frete\Result');
    }

    function it_can_calculate_with_error_response(Client $client, Result $result)
    {
        $response = new FakeResponse(true);
        $client->get('http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&nCdServico=40010%2C41106&sCepOrigem=&sCepDestino=&nVlPeso=&nCdFormato=1&nVlComprimento=&nVlAltura=&nVlLargura=&nVlDiametro=&sCdMaoPropria=N&nVlValorDeclarado=0&sCdAvisoRecebimento=N&StrRetorno=xml')->willReturn($response);

        # result
        $result->setSuccessful(false)->shouldBeCalled();
        $result->fill(Argument::any())->shouldBeCalled();
        $result->setError('')->shouldBeCalled();

        $this->calculate()->shouldBeAnInstanceOf('EscapeWork\Frete\Result');
    }

    function it_throw_an_exception_with_invalid_server_response(Client $client, Result $result)
    {
        $response = new FakeResponse(true, true);
        $client->get('http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&nCdServico=40010%2C41106&sCepOrigem=&sCepDestino=&nVlPeso=&nCdFormato=1&nVlComprimento=&nVlAltura=&nVlLargura=&nVlDiametro=&sCdMaoPropria=N&nVlValorDeclarado=0&sCdAvisoRecebimento=N&StrRetorno=xml')->willReturn($response);

        $this->shouldThrow('EscapeWork\Frete\FreteException')->during('calculate');
    }
}

class FakeResponse
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
