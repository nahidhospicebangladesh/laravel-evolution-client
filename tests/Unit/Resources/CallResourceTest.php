<?php
// tests/Unit/Resources/CallResourceTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Resources\Call;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

class CallResourceTest extends TestCase
{
    /**
     * @var Call
     */
    protected $callResource;

    /**
     * @var EvolutionService
     */
    protected $service;

    /** @test */
    public function it_can_make_fake_voice_call()
    {
        $result = $this->callResource->fakeCall('5511999999999', false, 30);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_make_fake_video_call()
    {
        $result = $this->callResource->fakeCall('5511999999999', true, 45);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_make_fake_call_with_default_parameters()
    {
        $result = $this->callResource->fakeCall('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_make_fake_call_with_custom_duration()
    {
        $result = $this->callResource->fakeCall('5511999999999', false, 120);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_make_fake_call_with_zero_duration()
    {
        $result = $this->callResource->fakeCall('5511999999999', false, 0);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_instance_name()
    {
        $this->assertEquals('test-instance', $this->callResource->getInstanceName());
    }

    /** @test */
    public function it_can_set_instance_name()
    {
        $this->callResource->setInstanceName('new-instance');
        $this->assertEquals('new-instance', $this->callResource->getInstanceName());
    }

    /** @test */
    public function it_can_make_fake_call_with_formatted_number()
    {
        $result = $this->callResource->fakeCall('+55 (11) 99999-9999', true, 60);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_make_fake_call_with_international_number()
    {
        $result = $this->callResource->fakeCall('+1234567890', false, 30);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_make_fake_call_with_empty_number()
    {
        $result = $this->callResource->fakeCall('', true, 15);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service->method('post')->willReturn([
            'status'  => 'success',
            'message' => 'Fake call sent',
        ]);

        $this->callResource = new Call($this->service, 'test-instance');
    }
}
