<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Chat
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
     * Create a new Chat resource instance.
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
     * Format phone number to be used with the API.
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        $number = preg_replace('/\D/', '', $phoneNumber);

        // Add @ to create a valid recipient id for the API
        return $number . '@c.us';
    }

    /**
     * Get all chats.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function all(): array
    {
        return $this->service->get("/chat/fetch/{$this->instanceName}");
    }

    /**
     * Get a specific chat.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function find(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->get("/chat/find/{$this->instanceName}", [
            'number' => $number,
        ]);
    }

    /**
     * Get chat messages.
     *
     * @param string $phoneNumber
     * @param int $count
     * @return array
     * @throws EvolutionApiException
     */
    public function messages(string $phoneNumber, int $count = 20): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->get("/chat/messages/{$this->instanceName}", [
            'number' => $number,
            'count' => $count,
        ]);
    }

    /**
     * Clear all messages in a chat.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function clearMessages(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/chat/clear/{$this->instanceName}", [
            'number' => $number,
        ]);
    }

    /**
     * Archive a chat.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function archive(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/chat/archive/{$this->instanceName}", [
            'number' => $number,
            'archive' => true,
        ]);
    }

    /**
     * Unarchive a chat.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function unarchive(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/chat/archive/{$this->instanceName}", [
            'number' => $number,
            'archive' => false,
        ]);
    }

    /**
     * Delete a chat.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function delete(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->delete("/chat/delete/{$this->instanceName}", [
            'number' => $number,
        ]);
    }

    /**
     * Mark chat as read.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function markAsRead(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/chat/read/{$this->instanceName}", [
            'number' => $number,
        ]);
    }

    /**
     * Start typing in a chat.
     *
     * @param string $phoneNumber
     * @param int $duration
     * @return array
     * @throws EvolutionApiException
     */
    public function startTyping(string $phoneNumber, int $duration = 1000): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/chat/presence/{$this->instanceName}", [
            'number' => $number,
            'presence' => 'composing',
            'duration' => $duration,
        ]);
    }

    /**
     * Stop typing in a chat.
     *
     * @param string $phoneNumber
     * @return array
     * @throws EvolutionApiException
     */
    public function stopTyping(string $phoneNumber): array
    {
        $number = $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/chat/presence/{$this->instanceName}", [
            'number' => $number,
            'presence' => 'paused',
        ]);
    }
}
