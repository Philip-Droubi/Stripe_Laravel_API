<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidRequestException extends Exception
{
    protected $message, $code, $previous;
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
    }

    public function report()
    {
        Log::error($this->getMessage() . "\nTrace:\n" . $this->__toString());
    }
}
