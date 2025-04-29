<?php
// src/Facades/Evolution.php

namespace SamuelTerra22\LaravelEvolutionClient\Facades;

use Illuminate\Support\Facades\Facade;
use SamuelTerra22\LaravelEvolutionClient\EvolutionApiClient;
use SamuelTerra22\LaravelEvolutionClient\Resources\Call;
use SamuelTerra22\LaravelEvolutionClient\Resources\Chat;
use SamuelTerra22\LaravelEvolutionClient\Resources\Group;
use SamuelTerra22\LaravelEvolutionClient\Resources\Instance;
use SamuelTerra22\LaravelEvolutionClient\Resources\Label;
use SamuelTerra22\LaravelEvolutionClient\Resources\Message;
use SamuelTerra22\LaravelEvolutionClient\Resources\Profile;
use SamuelTerra22\LaravelEvolutionClient\Resources\WebSocket;

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
