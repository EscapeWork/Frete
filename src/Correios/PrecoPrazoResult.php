<?php

namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\Result;

class PrecoPrazoResult extends Result
{
    public function getValor()
    {
        return isset($this['Valor']) ? $this['Valor'] : $this['cServico']['Valor'];
    }

    public function getPrazoEntrega()
    {
        return isset($this['PrazoEntrega']) ? $this['PrazoEntrega'] : $this['cServico']['PrazoEntrega'];
    }
}
