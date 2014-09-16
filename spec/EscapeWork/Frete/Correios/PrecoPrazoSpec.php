<?php

namespace spec\EscapeWork\Frete\Correios;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use GuzzleHttp\Client;

class PrecoPrazoSpec extends ObjectBehavior
{

    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_can_calculate_with_successful_response(Client $client)
    {
        $response = new Response;
        $client->get('http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&nCdServico=40010%2C41106&sCepOrigem=&sCepDestino=&nVlPeso=&nCdFormato=1&nVlComprimento=&nVlAltura=&nVlLargura=&nVlDiametro=&sCdMaoPropria=N&nVlValorDeclarado=0&sCdAvisoRecebimento=N&StrRetorno=xml')->willReturn($response);

        $this->calcular()->shouldBeAnInstanceOf('EscapeWork\Frete\Result');
    }
}

class Response
{

    public function xml()
    {
        $servico = (object) [
            'Codigo'                => '',
            'Valor'                 => '',
            'PrazoEntrega'          => '',
            'ValorMaoPropria'       => '',
            'ValorAvisoRecebimento' => '',
            'ValorValorDeclarado'   => '',
            'EntregaDomiciliar'     => '',
            'EntregaSabado'         => '',
            'Erro'                  => '0',
            'MsgErro'               => '',
        ];

        return (object) [
            'cServico' => $servico
        ];
    }
}
