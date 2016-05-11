<?php

namespace EscapeWork\Frete\Correios;

class BaseCorreios
{

    protected function xmlToArray($data)
    {
        return json_decode(json_encode((array) $data), 1);
    }
}
