<?php

namespace App\Exceptions;

use Exception;

class UndefinedAuthorAttrException extends Exception
{
    public function __construct()
    {
        $this->message = 'Undefined field for an Author record is passed to edit API.';
    }
}
