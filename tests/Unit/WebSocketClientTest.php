<?php

namespace SamuelTerra22\EvolutionLaravelClient\Tests\Unit;

use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use SamuelTerra22\EvolutionLaravelClient\Services\WebSocketClient;

class WebSocketClientTest extends TestCase
{
    /**
     * @var WebSocketClient
     */
    protected $webSocketClient;

    /**
     * @var LoopInterface
     */
    protected $loop;

    protected function setUp(): void
    {
        parent::setUp();

        // Since we don't want to actually connect to a WebSocket server in tests,
        // we'll mock or stub the necessary components
        $this->loop = $this->createMock(LoopInterface::class);

        // Create reflection class to access protected properties
        $reflectionClass = new \ReflectionClass(WebSocketClient::class);

        // Create a partial mock for WebSocketClient
        $this->webSocketClient = $this->getMockBuilder(WebSocketClient::class)
            ->setConstructorArgs([
                'ws://localhost:8080',
                'test-instance',
                'test-api-key',
                5,
                1.0
            ])
            ->onlyMethods(['connect', 'disconnect'])
            ->getMock();

        // Set the mock loop using reflection
        $loopProperty = $reflectionClass->getProperty('loop');
        $loopProperty->setAccessible(true);
        $loopProperty->setValue($this->webSocketClient, $this->loop);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(WebSocketClient::class, $this->webSocketClient);
    }

    /** @test */
    public function it_can_register_event_handlers()
    {
        $eventHandler = function ($data) {
            return $data;
        };

        $result = $this->webSocketClient->on('message', $eventHandler);

        $this->assertInstanceOf(WebSocketClient::class, $result);

        // Verify handler was registered (using reflection)
        $reflection = new \ReflectionClass($this->webSocketClient);
        $handlersProperty = $reflection->getProperty('handlers');
        $handlersProperty->setAccessible(true);
        $handlers = $handlersProperty->getValue($this->webSocketClient);

        $this->assertArrayHasKey('message', $handlers);
        $this->assertSame($eventHandler, $handlers['message']);
    }

    /** @test */
    public function it_can_register_multiple_event_handlers()
    {
        $messageHandler = function ($data) {
            return 'message: ' . json_encode($data);
        };

        $ackHandler = function ($data) {
            return 'ack: ' . json_encode($data);
        };

        $this->webSocketClient->on('message', $messageHandler);
        $this->webSocketClient->on('message.ack', $ackHandler);

        // Verify handlers were registered
        $reflection = new \ReflectionClass($this->webSocketClient);
        $handlersProperty = $reflection->getProperty('handlers');
        $handlersProperty->setAccessible(true);
        $handlers = $handlersProperty->getValue($this->webSocketClient);

        $this->assertCount(2, $handlers);
        $this->assertArrayHasKey('message', $handlers);
        $this->assertArrayHasKey('message.ack', $handlers);
        $this->assertSame($messageHandler, $handlers['message']);
        $this->assertSame($ackHandler, $handlers['message.ack']);
    }
}
