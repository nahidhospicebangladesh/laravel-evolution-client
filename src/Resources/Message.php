<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Message
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
     * Create a new Message resource instance.
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
     * Send a text message.
     *
     * @param string $phoneNumber
     * @param string $message
     * @param bool $isGroup
     * @return array
     * @throws EvolutionApiException
     */
    public function sendText(string $phoneNumber, string $message, bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/text/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'textMessage' => [
                'text' => $message,
            ],
        ]);
    }

    /**
     * Send an image message.
     *
     * @param string $phoneNumber
     * @param string $image
     * @param string $caption
     * @param bool $isGroup
     * @return array
     * @throws EvolutionApiException
     */
    public function sendImage(string $phoneNumber, string $image, string $caption = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/image/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'imageMessage' => [
                'image' => $image,
                'caption' => $caption,
            ],
        ]);
    }

    /**
     * Send a document message.
     *
     * @param string $phoneNumber
     * @param string $document
     * @param string $fileName
     * @param string $caption
     * @param bool $isGroup
     * @return array
     * @throws EvolutionApiException
     */
    public function sendDocument(string $phoneNumber, string $document, string $fileName, string $caption = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/document/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'documentMessage' => [
                'document' => $document,
                'fileName' => $fileName,
                'caption' => $caption,
            ],
        ]);
    }

    /**
     * Send a location message.
     *
     * @param string $phoneNumber
     * @param float $latitude
     * @param float $longitude
     * @param string $name
     * @param string $address
     * @param bool $isGroup
     * @return array
     * @throws EvolutionApiException
     */
    public function sendLocation(string $phoneNumber, float $latitude, float $longitude, string $name = '', string $address = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/location/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'locationMessage' => [
                'lat' => $latitude,
                'lng' => $longitude,
                'name' => $name,
                'address' => $address,
            ],
        ]);
    }

    /**
     * Send a contact message.
     *
     * @param string $phoneNumber
     * @param string $contactName
     * @param string $contactNumber
     * @param bool $isGroup
     * @return array
     * @throws EvolutionApiException
     */
    public function sendContact(string $phoneNumber, string $contactName, string $contactNumber, bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/contact/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'contactMessage' => [
                'displayName' => $contactName,
                'vcard' => "BEGIN:VCARD\nVERSION:3.0\nN:;{$contactName};;;\nFN:{$contactName}\nTEL;type=CELL;waid={$contactNumber}:{$contactNumber}\nEND:VCARD",
            ],
        ]);
    }
}
