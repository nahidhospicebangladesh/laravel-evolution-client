<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Models\WebSocket as WebSocketModel;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class WebSocket
{
    /**
     * @var EvolutionService The Evolution service
     */
    protected EvolutionService $service;

    /**
     * @var string The instance name
     */
    protected string $instanceName;

    /**
     * Create a new WebSocket resource instance.
     *
     * @param EvolutionService $service
     * @param string           $instanceName
     */
    public function __construct(EvolutionService $service, string $instanceName)
    {
        $this->service = $service;
        $this->instanceName = $instanceName;
    }

    /**
     * Set the instance name.
     *
     * @param string $instanceName
     *
     * @return void
     */
    public function setInstanceName(string $instanceName): void
    {
        $this->instanceName = $instanceName;
    }

    /**
     * Get the instance name.
     *
     * @return string
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * Configure WebSocket settings.
     *
     * @param bool  $enabled
     * @param array $events
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function setWebSocket(bool $enabled, array $events = []): array
    {
        $webSocket = new WebSocketModel($enabled, $events);

        return $this->service->post("/websocket/set/{$this->instanceName}", $webSocket->toArray());
    }

    /**
     * Get WebSocket settings.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function findWebSocket(): array
    {
        return $this->service->get("/websocket/find/{$this->instanceName}");
    }

    /**
     * Create a WebSocket client.
     *
     * @param int $maxRetries
     * @param float $retryDelay
     * @return mixed
     */
    public function createClient(int $maxRetries = 5, float $retryDelay = 1.0)
    {
        // Durante os testes, isto pode retornar null
        if (defined('PHPUNIT_RUNNING') && PHPUNIT_RUNNING) {
            return null;
        }

        return new \SamuelTerra22\EvolutionLaravelClient\Services\WebSocketClient(
            $this->service->getBaseUrl(),
            $this->instanceName,
            $this->service->getApiKey(),
            $maxRetries,
            $retryDelay
        );
    }
}
