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
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Chat getChatAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Group getGroupAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Message getMessageAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Instance getInstanceAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Call getCallAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Label getLabelAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\Profile getProfileAttribute()
 * @method static \SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket getWebsocketAttribute()
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
