<?php

namespace spec\EscapeWork\Frete\Correios;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EscapeWork\Frete\Correios\Data;
use EscapeWork\Frete\Correios\RastreamentoResult;
use GuzzleHttp\Client;
use Exception;

class RastreamentoSpec extends ObjectBehavior
{

    function let(Client $client, RastreamentoResult $result)
    {
        $this->beConstructedWith($client, $result);
    }

    function it_throw_an_exception_with_invalid_tipo(Client $client, RastreamentoResult $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setTipo', ['A']);
    }

    function it_throw_an_exception_with_invalid_resultado(Client $client, RastreamentoResult $result)
    {
        $this->shouldThrow('InvalidArgumentException')->during('setTipo', ['B']);
    }

    function it_can_set_objetos_with_valid_value(Client $client, RastreamentoResult $result)
    {
        $this->setObjetos('SQ458226057BR')->shouldReturn($this);
    }

    function it_can_set_objetos_with_valid_two_values(Client $client, RastreamentoResult $result)
    {
        $this->setObjetos(['SQ458226057BR', 'SQ458226057BR'])->shouldReturn($this);
    }

    function it_throw_an_frete_exception_with_invalid_server_response(Client $client, RastreamentoResult $result)
    {
        $response = new FakeRastreamentoResponse('ParseException');
        $data     = ['Usuario' => '', 'Senha' => '', 'Tipo' => 'L', 'Resultado' => 'U', 'Objetos' => ''];
        $client->post(Data::URL_RASTREAMENTO, ['form_params' => $data])->willReturn($response);

        $this->shouldThrow('EscapeWork\Frete\FreteException')->during('track');
    }

    function it_throw_an_frete_exception_with_error_in_response(Client $client, RastreamentoResult $result)
    {
        $response = new FakeRastreamentoResponse('error');
        $data     = ['Usuario' => '', 'Senha' => '', 'Tipo' => 'L', 'Resultado' => 'U', 'Objetos' => ''];
        $client->post(Data::URL_RASTREAMENTO, ['form_params' => $data])->willReturn($response);

        $this->shouldThrow('EscapeWork\Frete\FreteException')->during('track');
    }

    function it_can_track_with_one_object(Client $client, RastreamentoResult $result)
    {
        $response = new FakeRastreamentoResponse('object');
        $data     = ['Usuario' => '', 'Senha' => '', 'Tipo' => 'L', 'Resultado' => 'U', 'Objetos' => ''];
        $client->post(Data::URL_RASTREAMENTO, ['form_params' => $data])->willReturn($response);

        # result
        $result->fill(Argument::any())->shouldBeCalled();

        $this->track()->shouldBeAnInstanceOf('EscapeWork\Frete\Correios\RastreamentoResult');
    }
}

class FakeRastreamentoResponse
{

    protected $type = '';

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getBody()
    {
        return $this;
    }

    public function getContents()
    {
        switch ($this->type) {
            case 'ParseException':
                throw new Exception;
                break;

            case 'error':
                return '<?xml version="1.0" encoding="iso-8859-1" ?><sroxml><error>Objeto não encontrado</error></sroxml>';

            case 'object':
                return '<?xml version="1.0" encoding="iso-8859-1" ?><sroxml><objeto>
                    <numero>123</numero>
                    <evento>
                        <tipo>DO</tipo>
                        <status>0</status>
                    </evento>
                </objeto></sroxml>';

            case 'objects':
                return '<?xml version="1.0" encoding="iso-8859-1" ?><sroxml>
                    <objeto></objeto>
                    <objeto></objeto>
                </sroxml>';
        }
    }
}
