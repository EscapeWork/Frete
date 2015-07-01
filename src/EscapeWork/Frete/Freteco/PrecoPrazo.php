<?php namespace EscapeWork\Frete\Freteco;

use EscapeWork\Frete\FreteException;
use EscapeWork\Frete\Correios\PrecoPrazo as CorreiosPrecoPrazo;
use EscapeWork\Frete\Correios\PrecoPrazoResult;
use EscapeWork\Frete\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ParseException;
use InvalidArgumentException;

class PrecoPrazo extends CorreiosPrecoPrazo
{
    public function __construct(Client $client = null, PrecoPrazoResult $result = null)
    {
        parent::__construct($client, $result);        
        $this->data['apikey'] = '';
    }

    public function setApiKey($apiKey)
    {
        $this->data['apikey'] = $apiKey;
        return $this;
    }

    protected function buildUrl()
    {
        return Data::URL_PRECO_PRAZO . '?' . $this->getParameters();
    }
}
