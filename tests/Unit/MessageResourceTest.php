<?php
// tests/Unit/Resources/MessageResourceTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use SamuelTerra22\LaravelEvolutionClient\Resources\Message;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

class MessageResourceTest extends TestCase
{
    /**
     * @var Message
     */
    protected $messageResource;

    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var EvolutionService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'key'    => [
                    'id'        => '123456',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe'    => true,
                ],
            ])),
        ]);

        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->setConstructorArgs(['http://localhost:8080', 'test-api-key', 30])
            ->onlyMethods(['getClient', 'post'])
            ->getMock();

        $this->service->method('getClient')->willReturn($client);
        $this->service->method('post')->willReturn([
            'status' => 'success',
            'key'    => [
                'id'        => '123456',
                'remoteJid' => '5511999999999@c.us',
                'fromMe'    => true,
            ],
        ]);

        $this->messageResource = new Message($this->service, 'test-instance');
    }

    /** @test */
    public function it_can_send_text_message()
    {
        $result = $this->messageResource->sendText('5511999999999', 'Test message');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('key', $result);
    }

    /** @test */
    public function it_validates_phone_number_when_sending_text()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->messageResource->sendText('', 'Test message');
    }

    /** @test */
    public function it_validates_message_text_when_sending_text()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->messageResource->sendText('5511999999999', '');
    }

    /** @test */
    public function it_formats_phone_number_correctly()
    {
        // Create a subclass that makes the protected method public for testing
        $messageResource = new class($this->service, 'test-instance') extends Message {
            public function publicFormatPhoneNumber(string $phoneNumber): string
            {
                return $this->formatPhoneNumber($phoneNumber);
            }
        };

        // Test with regular number
        $this->assertEquals('5511999999999@c.us', $messageResource->publicFormatPhoneNumber('5511999999999'));

        // Test with formatted number
        $this->assertEquals('5511999999999@c.us', $messageResource->publicFormatPhoneNumber('+55 (11) 99999-9999'));
    }

    /** @test */
    public function it_can_send_location_message()
    {
        $result = $this->messageResource->sendLocation(
            '5511999999999',
            -23.5505,
            -46.6333,
            'São Paulo',
            'Paulista Avenue, 1000'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_contact_message()
    {
        $result = $this->messageResource->sendContact(
            '5511999999999',
            'Contact Name',
            '5511888888888'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }
}
