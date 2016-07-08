<?php

namespace EscapeWork\Frete\Correios;

use EscapeWork\Frete\Result;

class RastreamentoResult extends Result
{

    /**
     * Controle se pacote foi entregue
     * @var boolean
     */
    protected $delivered = false;

    /**
     * Controle se pacote está em transito
     * @var boolean
     */
    protected $inTransit = false;

    public function delivered()
    {
        return $this->delivered;
    }

    public function inTransit()
    {
        return $this->inTransit;
    }

    public function fill($data)
    {
        $this->checkIfIsDelivered($data);
        $this->checkIfIsInTransit($data);

        return parent::fill($data);
    }

    protected function checkIfIsDelivered($data)
    {
        $tipos  = ['BDE', 'BDI', 'BDR'];
        $status = ['0', '1', '01', '00'];

        if (in_array($data->evento->tipo, $tipos) && in_array($data->evento->status, $status)) {
            $this->delivered = true;
        }
    }

    protected function checkIfIsInTransit($data)
    {
        if ($data->evento->tipo == 'DO') {
            $this->inTransit = true;
        }
    }
}
