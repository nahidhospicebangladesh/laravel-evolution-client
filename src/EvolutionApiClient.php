<?php

namespace SamuelTerra22\EvolutionLaravelClient;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Resources\Call;
use SamuelTerra22\EvolutionLaravelClient\Resources\Chat;
use SamuelTerra22\EvolutionLaravelClient\Resources\Group;
use SamuelTerra22\EvolutionLaravelClient\Resources\Instance;
use SamuelTerra22\EvolutionLaravelClient\Resources\Label;
use SamuelTerra22\EvolutionLaravelClient\Resources\Message;
use SamuelTerra22\EvolutionLaravelClient\Resources\Profile;
use SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class EvolutionApiClient
{
    /**
     * @var Chat The Chat resource
     */
    public Chat $chat;
    /**
     * @var Group The Group resource
     */
    public Group $group;
    /**
     * @var Message The Message resource
     */
    public Message $message;
    /**
     * @var Instance The Instance resource
     */
    public Instance $instance;
    /**
     * @var Call The Call resource
     */
    public Call $call;
    /**
     * @var Label The Label resource
     */
    public Label $label;
    /**
     * @var Profile The Profile resource
     */
    public Profile $profile;
    /**
     * @var WebSocket The WebSocket resource
     */
    public WebSocket $websocket;
    /**
     * @var string The instance name
     */
    protected string $instanceName;
    /**
     * @var EvolutionService The Evolution API service
     */
    protected EvolutionService $service;

    /**
     * Create a new EvolutionApiClient instance.
     *
     * @param EvolutionService $service
     * @param string           $instanceName
     */
    public function __construct(EvolutionService $service, string $instanceName = 'default')
    {
        $this->service = $service;
        $this->instanceName = $instanceName;

        // Initialize resources
        $this->chat = new Chat($service, $instanceName);
        $this->group = new Group($service, $instanceName);
        $this->message = new Message($service, $instanceName);
        $this->instance = new Instance($service, $instanceName);
        $this->call = new Call($service, $instanceName);
        $this->label = new Label($service, $instanceName);
        $this->profile = new Profile($service, $instanceName);
        $this->websocket = new WebSocket($service, $instanceName);
    }

    /**
     * Set the instance name.
     *
     * @param string $instanceName
     *
     * @return self
     */
    public function instance(string $instanceName): self
    {
        $this->instanceName = $instanceName;

        // Update instance name in all resources
        $this->chat->setInstanceName($instanceName);
        $this->group->setInstanceName($instanceName);
        $this->message->setInstanceName($instanceName);
        $this->instance->setInstanceName($instanceName);
        $this->call->setInstanceName($instanceName);
        $this->label->setInstanceName($instanceName);
        $this->profile->setInstanceName($instanceName);
        $this->websocket->setInstanceName($instanceName);

        return $this;
    }

    /**
     * Get the QR code for the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function getQrCode(): array
    {
        return $this->instance->getQrCode();
    }

    /**
     * Check if the instance is connected.
     *
     * @return bool
     * @throws EvolutionApiException
     */
    public function isConnected(): bool
    {
        return $this->instance->isConnected();
    }

    /**
     * Disconnect the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function disconnect(): array
    {
        return $this->instance->disconnect();
    }

    /**
     * Send a text message.
     *
     * @param string $phoneNumber
     * @param string $message
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendText(string $phoneNumber, string $message): array
    {
        return $this->message->sendText($phoneNumber, $message);
    }
}
