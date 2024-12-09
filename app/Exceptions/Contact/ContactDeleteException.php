<?php

namespace App\Exceptions\Contact;

use Exception;

class ContactDeleteException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Erro ao excluir contato: {$message}");
    }
}
