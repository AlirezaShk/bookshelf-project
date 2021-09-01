<?php

namespace App\Exceptions;

use Exception;

class InvalidISBNLengthException extends Exception
{
    public function __construct()
    {
        $this->message = 'Invalid ISBN length. Only ISBN-13 and ISBN-10 are valid.';
    }
}
