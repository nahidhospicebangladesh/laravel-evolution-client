<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Models\Call as CallModel;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Call
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
     * Create a new Call resource instance.
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
     * Get the instance name.
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
     * Make a fake call.
     *
     * @param string $number
     * @param bool   $isVideo
     * @param int    $callDuration
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function fakeCall(string $number, bool $isVideo = false, int $callDuration = 45): array
    {
        $call = new CallModel($number, $isVideo, $callDuration);

        return $this->service->post("/call/offer/{$this->instanceName}", $call->toArray());
    }
}
