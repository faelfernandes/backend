<?php

namespace App\Exceptions\Contact;

use Exception;

class ContactCreationException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Erro ao criar contato: {$message}");
    }
}
