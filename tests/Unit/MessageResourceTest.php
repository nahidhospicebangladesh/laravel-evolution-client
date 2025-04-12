<?php

namespace SamuelTerra22\EvolutionLaravelClient\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SamuelTerra22\EvolutionLaravelClient\Models\Button;
use SamuelTerra22\EvolutionLaravelClient\Models\ListRow;
use SamuelTerra22\EvolutionLaravelClient\Models\ListSection;
use SamuelTerra22\EvolutionLaravelClient\Resources\Message;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '12345',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true
                ]
            ])),
        ]);

        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->setConstructorArgs(['http://localhost:8080', 'test-api-key', 30])
            ->onlyMethods(['getClient'])
            ->getMock();

        $this->service->method('getClient')->willReturn($httpClient);

        $this->messageResource = new Message($this->service, 'test-instance');
    }

    /** @test */
    public function it_can_send_text_message()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '12345',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true
                ]
            ]))
        );

        $result = $this->messageResource->sendText('5511999999999', 'Test message');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('12345', $result['key']['id']);
    }

    /** @test */
    public function it_can_send_text_message_with_options()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '12345',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true
                ]
            ]))
        );

        $result = $this->messageResource->sendText(
            '5511999999999',
            'Test message with link https://example.com',
            false,
            1000,
            true
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_poll_message()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '12345',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true
                ]
            ]))
        );

        $result = $this->messageResource->sendPoll(
            '5511999999999',
            'Favorite Color?',
            1,
            ['Red', 'Green', 'Blue', 'Yellow']
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_list_message()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '12345',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true
                ]
            ]))
        );

        $rows1 = [
            new ListRow('Option 1', 'Description 1', 'opt1'),
            new ListRow('Option 2', 'Description 2', 'opt2')
        ];

        $sections = [
            new ListSection('Section 1', $rows1)
        ];

        $result = $this->messageResource->sendList(
            '5511999999999',
            'Test List',
            'Choose an option',
            'View Options',
            'Footer text',
            $sections
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_buttons_message()
    {
        // Add a response for the request
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '12345',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true
                ]
            ]))
        );

        $buttons = [
            new Button('reply', 'Yes', ['id' => 'btn-yes']),
            new Button('reply', 'No', ['id' => 'btn-no'])
        ];

        $result = $this->messageResource->sendButtons(
            '5511999999999',
            'Confirmation',
            'Do you want to proceed?',
            'Choose an option',
            $buttons
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_format_phone_number()
    {
        $method = new \ReflectionMethod(Message::class, 'formatPhoneNumber');
        $method->setAccessible(true);

        // Test with regular number
        $this->assertEquals('5511999999999@c.us', $method->invoke($this->messageResource, '5511999999999'));

        // Test with formatted number
        $this->assertEquals('5511999999999@c.us', $method->invoke($this->messageResource, '+55 (11) 99999-9999'));
    }
}
