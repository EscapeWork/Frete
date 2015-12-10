<?php

namespace spec\EscapeWork\Frete\Freteco;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EscapeWork\Frete\Correios\PrecoPrazoResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class PrecoPrazoSpec extends ObjectBehavior
{

    function let(Client $client, PrecoPrazoResult $result)
    {
        $this->beConstructedWith($client, $result);
    }

    function it_can_calculate_with_one_result(Client $client, PrecoPrazoResult $result)
    {
        $response = new FakePrecoPrazoResponse('preco');
        $client->get('https://frete.co/api/v1/carriers/correios.aspx?nCdEmpresa=&sDsSenha=&nCdServico=40010%2C41106&sCepOrigem=&sCepDestino=&nVlPeso=&nCdFormato=1&nVlComprimento=&nVlAltura=&nVlLargura=&nVlDiametro=&sCdMaoPropria=N&nVlValorDeclarado=0&sCdAvisoRecebimento=N&apikey=&StrRetorno=xml')->willReturn($response);

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

    public function getBody()
    {
        switch ($this->type) {
            case 'exception':
                throw new RequestException('x', new Request('x','x'));

            case 'error':

                return new FakePrecoPrazoStream(
                    [
                        'cServico' => [
                            'Erro'    => '1',
                            'MsgErro' => 'fucking errors!',
                        ]
                    ]
                );

            case 'error-array':
                return new FakePrecoPrazoStream(
                    [
                        'cServico' => [
                            'Erro'    => '1',
                            'MsgErro' => ['fucking errors with array!'],
                        ]
                    ]
                );

            case 'preco':
                return new FakePrecoPrazoStream(
                    [
                        'cServico' => [
                            'Codigo' => 1,
                            'Erro'   => '0',
                        ]
                    ]
                );

            case 'precos':
                return new FakePrecoPrazoStream(
                    [
                        'cServico' => [
                            ['Erro' => '0'],
                        ]
                    ]
                );
        }
    }
}

class FakePrecoPrazoStream implements \Psr\Http\Message\StreamInterface
{

    protected $content = '';

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getContents()
    {
        return $this->content;
    }

    public function __toString(){}

    public function close(){}

    public function detach(){}

    public function getSize(){}

    public function tell(){}

    public function eof(){}

    public function isSeekable(){}

    public function seek($offset, $whence = SEEK_SET){}

    public function rewind(){}

    public function isWritable(){}

    public function write($string){}

    public function isReadable(){}

    public function read($length){}

    public function getMetadata($key = null){}
}
