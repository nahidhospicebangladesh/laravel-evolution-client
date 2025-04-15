<?php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SamuelTerra22\LaravelEvolutionClient\EvolutionApiClient;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;
use SamuelTerra22\LaravelEvolutionClient\Tests\TestCase;

class EvolutionApiClientTest extends TestCase
{
    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var EvolutionApiClient
     */
    protected $client;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(EvolutionApiClient::class, $this->client);
    }

    /** @test */
    public function it_can_set_instance_name()
    {
        $this->client->instance('new-instance');

        $this->assertEquals('new-instance', $this->client->instance->getInstanceName());
    }

    /** @test */
    public function it_provides_access_to_all_resources()
    {
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Chat', $this->client->chat);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Group', $this->client->group);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Message', $this->client->message);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Instance', $this->client->instance);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Call', $this->client->call);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Label', $this->client->label);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\Profile', $this->client->profile);
        $this->assertInstanceOf('SamuelTerra22\LaravelEvolutionClient\Resources\WebSocket', $this->client->websocket);
    }

    /** @test */
    public function it_updates_instance_name_in_all_resources()
    {
        $instanceName = 'updated-instance';
        $this->client->instance($instanceName);

        $this->assertEquals($instanceName, $this->client->chat->getInstanceName());
        $this->assertEquals($instanceName, $this->client->group->getInstanceName());
        $this->assertEquals($instanceName, $this->client->message->getInstanceName());
        $this->assertEquals($instanceName, $this->client->instance->getInstanceName());
        $this->assertEquals($instanceName, $this->client->call->getInstanceName());
        $this->assertEquals($instanceName, $this->client->label->getInstanceName());
        $this->assertEquals($instanceName, $this->client->profile->getInstanceName());
        $this->assertEquals($instanceName, $this->client->websocket->getInstanceName());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status'  => 'success',
                'message' => 'Mock response',
            ])),
        ]);

        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient   = new Client(['handler' => $handlerStack]);

        // Mock the EvolutionService but allow actual method calls
        $service = $this->getMockBuilder(EvolutionService::class)
            ->setConstructorArgs(['http://localhost:8080', 'test-api-key', 30])
            ->onlyMethods(['getClient'])
            ->getMock();

        $service->method('getClient')->willReturn($httpClient);

        $this->client = new EvolutionApiClient($service, 'test-instance');
    }
}
