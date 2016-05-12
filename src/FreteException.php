<?php

namespace EscapeWork\Frete;

use Exception;

class FreteException extends Exception
{

    /**
     * Error code
     */
    protected $errorCode;

    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
