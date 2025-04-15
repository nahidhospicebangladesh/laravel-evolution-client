<?php

namespace SamuelTerra22\EvolutionLaravelClient\Facades;

use Illuminate\Support\Facades\Facade;
use SamuelTerra22\EvolutionLaravelClient\EvolutionApiClient;
use SamuelTerra22\EvolutionLaravelClient\Resources\Call;
use SamuelTerra22\EvolutionLaravelClient\Resources\Chat;
use SamuelTerra22\EvolutionLaravelClient\Resources\Group;
use SamuelTerra22\EvolutionLaravelClient\Resources\Instance;
use SamuelTerra22\EvolutionLaravelClient\Resources\Label;
use SamuelTerra22\EvolutionLaravelClient\Resources\Message;
use SamuelTerra22\EvolutionLaravelClient\Resources\Profile;
use SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket;

/**
 * @method static EvolutionApiClient instance(string $instanceName)
 * @method static array getQrCode()
 * @method static bool isConnected()
 * @method static array disconnect()
 * @method static array sendText(string $phoneNumber, string $message)
 * @method static Chat getChatAttribute()
 * @method static Group getGroupAttribute()
 * @method static Message getMessageAttribute()
 * @method static Instance getInstanceAttribute()
 * @method static Call getCallAttribute()
 * @method static Label getLabelAttribute()
 * @method static Profile getProfileAttribute()
 * @method static WebSocket getWebsocketAttribute()
 *
 * @see EvolutionApiClient
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
