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

        $evento = $this->getLastEvent($data);

        if (in_array($evento->tipo, $tipos) && in_array($evento->status, $status)) {
            $this->delivered = true;
        }
    }

    protected function checkIfIsInTransit($data)
    {
        $evento = $this->getLastEvent($data);

        if ($evento->tipo == 'DO') {
            $this->inTransit = true;
        }
    }

    /**
     * Retorna último evento
     * @param $data
     * @return object
     */
    protected function getLastEvent($data)
    {
        if (is_array($data->evento)) {
            return reset($data->evento);
        }

        return $data->evento;
    }
}
