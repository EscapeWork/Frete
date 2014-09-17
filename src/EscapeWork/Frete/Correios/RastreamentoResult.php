<?php namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\Result;

class RastreamentoResult extends Result
{

    /**
     * Entregue
     * @var boolean
     */
    protected $delivered = false;

    public function delivered()
    {
        return $this->delivered;
    }
}
