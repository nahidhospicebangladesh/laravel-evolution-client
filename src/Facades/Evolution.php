<?php

namespace SamuelTerra22\EvolutionLaravelClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \SamuelTerra22\EvolutionLaravelClient\EvolutionApiClient instance(string $instanceName)
 * @method static array getQrCode()
 * @method static bool isConnected()
 * @method static array disconnect()
 * @method static array sendText(string $phoneNumber, string $message)
 *
 * @see \SamuelTerra22\EvolutionLaravelClient\EvolutionApiClient
 */
class Evolution extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'evolution';
    }
}
