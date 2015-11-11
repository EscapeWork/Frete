<?php namespace EscapeWork\Frete\Correios;

class BaseCorreios
{

    protected function xmlToArray($data)
    {
        if (is_string($data)) {
            $data = simplexml_load_string($data);
        }

        return json_decode(json_encode((array) $data), 1);
    }
}
