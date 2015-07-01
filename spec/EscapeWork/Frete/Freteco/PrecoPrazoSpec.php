<?php

namespace spec\EscapeWork\Frete\Freteco;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EscapeWork\Frete\Correios\PrecoPrazoResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ParseException;

class PrecoPrazoSpec extends ObjectBehavior
{

    function let(Client $client, PrecoPrazoResult $result)
    {
        $this->beConstructedWith($client, $result);
    }

    function it_can_calculate_with_one_result(Client $client, PrecoPrazoResult $result)
    {
        $response = new FakePrecoPrazoResponse('preco');
        $client->get('https://frete.co/api/v1/carriers/correios?nCdEmpresa=&sDsSenha=&nCdServico=40010%2C41106&sCepOrigem=&sCepDestino=&nVlPeso=&nCdFormato=1&nVlComprimento=&nVlAltura=&nVlLargura=&nVlDiametro=&sCdMaoPropria=N&nVlValorDeclarado=0&sCdAvisoRecebimento=N&apikey=&StrRetorno=xml')->willReturn($response);

        $result->fill(Argument::any())->shouldBeCalled();
        $this->calculate()->shouldBeAnInstanceOf('EscapeWork\Frete\Correios\PrecoPrazoResult');
    }
}

class FakePrecoPrazoResponse
{

    protected $type = '';

    public function __construct($type = '')
    {
        $this->type = $type;
    }

    public function xml()
    {
        switch ($this->type) {
            case 'exception':
                throw new ParseException;

            case 'error':
                return json_decode(json_encode([
                    'cServico' => [
                        'Erro'    => '1',
                        'MsgErro' => 'fucking errors!',
                    ]
                ]));

            case 'error-array':
                return json_decode(json_encode([
                    'cServico' => [
                        'Erro'    => '1',
                        'MsgErro' => ['fucking errors with array!'],
                    ]
                ]));

            case 'preco':
                return json_decode(json_encode([
                    'cServico' => [
                        'Codigo' => 1,
                        'Erro'   => '0',
                    ]
                ]));

            case 'precos':
                return json_decode(json_encode([
                    'cServico' => [
                        ['Erro' => '0'],
                    ]
                ]));
        }
    }
}
