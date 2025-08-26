<?php

namespace Modules\Core\App\Exceptions;


use Exception;

class BlException extends Exception
{
    // Tu peux ajouter des propriétés si besoin (code, contexte, etc.)
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }
}