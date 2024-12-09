<?php

namespace App\Exceptions\Contact;

use Exception;

class ContactExportException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Erro ao exportar contatos: {$message}");
    }
}
