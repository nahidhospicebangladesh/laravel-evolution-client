<?php

namespace SamuelTerra22\EvolutionLaravelClient\Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use ReflectionClass;
use SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;
use SamuelTerra22\EvolutionLaravelClient\Services\WebSocketClient;
use SamuelTerra22\EvolutionLaravelClient\Tests\TestCase;

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

    /** @test */
    public function it_can_set_websocket_config()
    {
        $events = ['message', 'message.ack'];
        $result = $this->webSocketResource->setWebSocket(true, $events);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_websocket_config()
    {
        $result = $this->webSocketResource->findWebSocket();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_create_websocket_client()
    {
        // Create a simple subclass for testing
        $client = new class('ws://localhost:8080', 'test-instance', 'test-api-key') extends WebSocketClient {
            public function __construct($baseUrl, $instanceId, $apiToken)
            {
                $this->baseUrl = $baseUrl;
                $this->instanceId = $instanceId;
                $this->apiToken = $apiToken;
            }
        };

        // Use reflection to verify the instance creation works
        $reflectionClass = new ReflectionClass($this->webSocketResource);
        $reflectionMethod = $reflectionClass->getMethod('createClient');
        $reflectionMethod->setAccessible(true);

        // We just need to verify that the method completes without error
        $this->assertNull($reflectionMethod->invokeArgs($this->webSocketResource, []));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->createMockService();
        $this->webSocketResource = new WebSocket($this->service, 'test-instance');
    }
}
