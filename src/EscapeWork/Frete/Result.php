<?php namespace EscapeWork\Frete;

use ArrayAccess;

class Result implements ArrayAccess
{

    /**
     * Variavel pra verificar se o resultado está OK
     * @var bool
     */
    protected $successful = false;

    /**
     * Mensagem de erro
     * @var string
     */
    protected $error;

    /**
     * Attributes
     */
    protected $attributes = [];

    public function __construct($data = array())
    {
        $this->fill($data);
    }

    public function fill($data)
    {
        foreach ((array) $data as $field => $value) {
            $this->attributes[$field] = $value;
        }
    }

    public function setSuccessful($successful)
    {
        $this->successful = (boolean) $successful;
    }

    public function successful()
    {
        return $this->successful;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function __get($key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function offsetExists($key)
    {
        return isset($this->attributes[$key]);
    }

    public function offsetGet($key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function offsetUnset($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
        }
    }
}
