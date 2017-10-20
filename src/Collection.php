<?php

namespace EscapeWork\Frete;

use EscapeWork\Frete\Correios\PrecoPrazoResult;
use Exception;
use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Items
     * @var array
     */
    protected $items = array();

    public function __construct($items = array())
    {
        $this->items = (array) $items;
    }

    public function all()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    /**
     * @return Correios\PrecoPrazoResult
     */
    public function getFirstResultWithoutError()
    {
        foreach ($this->items as $item) {
            if (! in_array($item['Erro'], ['0', '010'])) {
                continue;
            }

            return $item;
        }

        throw new Exception('All items on this collections have errors');
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
