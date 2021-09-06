<?php

namespace App\Exceptions;

use Exception;

class UndefinedBookAttrException extends Exception
{
    public function __construct()
    {
        $this->message = 'Undefined field for a Book record is passed to edit API.';
    }
}
