<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Models\Label as LabelModel;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Label
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
     * Create a new Label resource instance.
     *
     * @param EvolutionService $service
     * @param string $instanceName
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
     * @return void
     */
    public function setInstanceName(string $instanceName): void
    {
        $this->instanceName = $instanceName;
    }

    /**
     * Find all labels.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function findLabels(): array
    {
        return $this->service->get("/label/findLabels/{$this->instanceName}");
    }

    /**
     * Add or remove a label to/from a chat.
     *
     * @param string $number
     * @param string $labelId
     * @param string $action
     * @return array
     * @throws EvolutionApiException
     */
    public function handleLabel(string $number, string $labelId, string $action): array
    {
        $label = new LabelModel($number, $labelId, $action);

        return $this->service->post("/label/handleLabel/{$this->instanceName}", $label->toArray());
    }

    /**
     * Add a label to a chat.
     *
     * @param string $number
     * @param string $labelId
     * @return array
     * @throws EvolutionApiException
     */
    public function addLabel(string $number, string $labelId): array
    {
        return $this->handleLabel($number, $labelId, 'add');
    }

    /**
     * Remove a label from a chat.
     *
     * @param string $number
     * @param string $labelId
     * @return array
     * @throws EvolutionApiException
     */
    public function removeLabel(string $number, string $labelId): array
    {
        return $this->handleLabel($number, $labelId, 'remove');
    }
}
