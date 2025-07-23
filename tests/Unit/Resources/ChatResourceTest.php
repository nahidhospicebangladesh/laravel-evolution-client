<?php
// tests/Unit/Resources/ChatResourceTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Resources\Chat;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

class ChatResourceTest extends TestCase
{
    /**
     * @var Chat
     */
    protected $chatResource;

    /**
     * @var EvolutionService
     */
    protected $service;

    /** @test */
    public function it_can_get_all_chats()
    {
        $result = $this->chatResource->all();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('chats', $result);
    }

    /** @test */
    public function it_can_find_chat()
    {
        $result = $this->chatResource->find('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_chat_messages()
    {
        $result = $this->chatResource->messages('5511999999999', 20);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('messages', $result);
    }

    /** @test */
    public function it_can_mark_chat_as_read()
    {
        $result = $this->chatResource->markAsRead('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_start_typing()
    {
        $result = $this->chatResource->startTyping('5511999999999', 2000);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_stop_typing()
    {
        $result = $this->chatResource->stopTyping('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_clear_chat_messages()
    {
        $result = $this->chatResource->clearMessages('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_archive_chat()
    {
        $result = $this->chatResource->archive('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_unarchive_chat()
    {
        $result = $this->chatResource->unarchive('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_delete_chat()
    {
        $this->service->method('delete')->willReturn([
            'status'  => 'success',
            'message' => 'Chat deleted successfully',
        ]);

        $result = $this->chatResource->delete('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_instance_name()
    {
        $this->assertEquals('test-instance', $this->chatResource->getInstanceName());
    }

    /** @test */
    public function it_can_set_instance_name()
    {
        $this->chatResource->setInstanceName('new-instance');
        $this->assertEquals('new-instance', $this->chatResource->getInstanceName());
    }

    /** @test */
    public function it_formats_phone_number_correctly()
    {
        // We need to create a way to test the protected method
        $chatResourceMock = new class($this->service, 'test') extends \SamuelTerra22\LaravelEvolutionClient\Resources\Chat {
            public function publicFormatPhoneNumber(string $phoneNumber): string
            {
                return $this->formatPhoneNumber($phoneNumber);
            }
        };

        $this->assertEquals('5511999999999@c.us', $chatResourceMock->publicFormatPhoneNumber('5511999999999'));
        $this->assertEquals('5511999999999@c.us', $chatResourceMock->publicFormatPhoneNumber('+55 (11) 99999-9999'));
        $this->assertEquals('1234567890@c.us', $chatResourceMock->publicFormatPhoneNumber('+1 (234) 567-890'));
    }

    /** @test */
    public function it_can_get_messages_with_custom_count()
    {
        $result = $this->chatResource->messages('5511999999999', 50);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('messages', $result);
    }

    /** @test */
    public function it_can_start_typing_with_custom_duration()
    {
        $result = $this->chatResource->startTyping('5511999999999', 5000);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service->method('get')->willReturn([
            'status' => 'success',
            'chats'  => [
                [
                    'id'          => '5511999999999@c.us',
                    'name'        => 'Contact Name',
                    'unreadCount' => 0,
                ],
            ],
            'messages' => [
                [
                    'key' => [
                        'remoteJid' => '5511999999999@c.us',
                        'fromMe'    => true,
                        'id'        => '12345',
                    ],
                    'message' => [
                        'conversation' => 'Hello',
                    ],
                    'timestamp' => 1678901234,
                ],
            ],
        ]);

        $this->service->method('post')->willReturn([
            'status'  => 'success',
            'message' => 'Operation successful',
        ]);

        $this->chatResource = new Chat($this->service, 'test-instance');
    }
}
