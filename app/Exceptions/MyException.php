<?php
namespace App\Exceptions;

use Exception;

class MyException extends \Exception
{
    protected $message;
    protected $httpCode;
    protected $code;

    public function __construct($message = "", $code = "", $httpCode = 500)
    {

        $this->httpCode = $httpCode;
        $this->code = $code;
        $this->message = $message;

        //parent::__construct($message, $code, $previous);
    }

    public function message()
    {
        return $this->message;
    }

    public function httpCode()
    {
        return $this->httpCode;
    }

    public function code()
    {
        return $this->code;
    }
}