<?php

namespace App\Exceptions\Contact;

use Exception;

class ContactUpdateException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Erro ao atualizar contato: {$message}");
    }
}
