<?php

namespace App\Exceptions\Contact;

use Exception;

class ContactListException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Erro ao listar contatos: {$message}");
    }
}
