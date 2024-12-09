<?php

namespace App\Exceptions\Contact;

use Exception;

class ContactNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Contato não encontrado.');
    }
}
