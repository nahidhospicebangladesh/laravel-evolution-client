<?php

namespace SamuelTerra22\EvolutionLaravelClient\Exceptions;

use Exception;
use Throwable;

class EvolutionApiException extends Exception
{
    /**
     * Create a new Evolution API exception instance.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
