<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Instance
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
     * Create a new Instance resource instance.
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
     * Get the current instance name.
     *
     * @return string
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
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
     * Get the QR code for the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function getQrCode(): array
    {
        return $this->service->get("/instance/qrcode/{$this->instanceName}");
    }

    /**
     * Check if the instance is connected.
     *
     * @return bool
     * @throws EvolutionApiException
     */
    public function isConnected(): bool
    {
        $status = $this->getStatus();

        return isset($status['status']) && $status['status'] === 'connected';
    }

    /**
     * Get the status of the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function getStatus(): array
    {
        return $this->service->get("/instance/status/{$this->instanceName}");
    }

    /**
     * Connect the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function connect(): array
    {
        return $this->service->post("/instance/connect/{$this->instanceName}");
    }

    /**
     * Disconnect the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function disconnect(): array
    {
        return $this->service->delete("/instance/logout/{$this->instanceName}");
    }

    /**
     * Delete the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function delete(): array
    {
        return $this->service->delete("/instance/delete/{$this->instanceName}");
    }

    /**
     * Restart the instance.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function restart(): array
    {
        return $this->service->post("/instance/restart/{$this->instanceName}");
    }

    /**
     * Set the webhook URL for the instance.
     *
     * @param string $url
     * @param array  $events
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function setWebhook(string $url, array $events = []): array
    {
        return $this->service->post("/instance/webhook/{$this->instanceName}", [
            'url' => $url,
            'events' => $events,
        ]);
    }
}
