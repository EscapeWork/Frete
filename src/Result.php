<?php

namespace EscapeWork\Frete;

use ArrayAccess;

class Result implements ArrayAccess
{
    /**
     * Attributes
     */
    protected $attributes = [];

    public function fill($data)
    {
        foreach ((array) $data as $field => $value) {
            $this->attributes[$field] = $value;
        }
    }

    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
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

    public function __toString()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
