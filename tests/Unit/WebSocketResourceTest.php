<?php

namespace SamuelTerra22\EvolutionLaravelClient\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SamuelTerra22\EvolutionLaravelClient\Models\WebSocket as WebSocketModel;
use SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;
use SamuelTerra22\EvolutionLaravelClient\Services\WebSocketClient;

class WebSocketResourceTest extends TestCase
{
    /**
     * @var WebSocket
     */
    protected $webSocketResource;

    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var EvolutionService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'enabled' => true,
                'events' => ['message', 'message.ack']
            ])),
        ]);

        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->setConstructorArgs(['http://localhost:8080', 'test-api-key', 30])
            ->onlyMethods(['getClient', 'getBaseUrl', 'getApiKey'])
            ->getMock();

        $this->service->method('getClient')->willReturn($httpClient);
        $this->service->method('getBaseUrl')->willReturn('http://localhost:8080');
        $this->service->method('getApiKey')->willReturn('test-api-key');

        $this->webSocketResource = new WebSocket($this->service, 'test-instance');
    }

    /** @test */
    public function it_can_set_websocket_config()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'enabled' => true,
                'events' => ['message', 'message.ack']
            ]))
        );

        $events = ['message', 'message.ack'];
        $result = $this->webSocketResource->setWebSocket(true, $events);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertTrue($result['enabled']);
        $this->assertEquals($events, $result['events']);
    }

    /** @test */
    public function it_can_find_websocket_config()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'enabled' => true,
                'events' => ['message', 'message.ack']
            ]))
        );

        $result = $this->webSocketResource->findWebSocket();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertTrue($result['enabled']);
        $this->assertEquals(['message', 'message.ack'], $result['events']);
    }

    /** @test */
    public function it_can_create_websocket_client()
    {
        $client = $this->webSocketResource->createClient();

        $this->assertInstanceOf(WebSocketClient::class, $client);

        // Check internal properties using reflection
        $reflection = new \ReflectionClass($client);

        $baseUrlProperty = $reflection->getProperty('baseUrl');
        $baseUrlProperty->setAccessible(true);
        $this->assertEquals('ws://localhost:8080', $baseUrlProperty->getValue($client));

        $instanceIdProperty = $reflection->getProperty('instanceId');
        $instanceIdProperty->setAccessible(true);
        $this->assertEquals('test-instance', $instanceIdProperty->getValue($client));

        $apiTokenProperty = $reflection->getProperty('apiToken');
        $apiTokenProperty->setAccessible(true);
        $this->assertEquals('test-api-key', $apiTokenProperty->getValue($client));
    }
}
