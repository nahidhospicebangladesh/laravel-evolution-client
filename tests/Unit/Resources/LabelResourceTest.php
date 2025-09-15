<?php
// tests/Unit/Resources/LabelResourceTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Resources\Label;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

class LabelResourceTest extends TestCase
{
    /**
     * @var Label
     */
    protected $labelResource;

    /**
     * @var EvolutionService
     */
    protected $service;

    /** @test */
    public function it_can_find_labels()
    {
        $result = $this->labelResource->findLabels();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('labels', $result);
    }

    /** @test */
    public function it_can_add_label()
    {
        $result = $this->labelResource->addLabel('5511999999999', 'label-id-123');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_remove_label()
    {
        $result = $this->labelResource->removeLabel('5511999999999', 'label-id-123');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_handle_label()
    {
        $result = $this->labelResource->handleLabel('5511999999999', 'label-id-123', 'add');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_instance_name()
    {
        $this->assertEquals('test-instance', $this->labelResource->getInstanceName());
    }

    /** @test */
    public function it_can_set_instance_name()
    {
        $this->labelResource->setInstanceName('new-instance');
        $this->assertEquals('new-instance', $this->labelResource->getInstanceName());
    }

    /** @test */
    public function it_can_add_label_with_formatted_phone_number()
    {
        $result = $this->labelResource->addLabel('+55 (11) 99999-9999', 'label_special');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_remove_label_with_formatted_phone_number()
    {
        $result = $this->labelResource->removeLabel('+55 (11) 88888-8888', 'label_work');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_handle_label_remove_action()
    {
        $result = $this->labelResource->handleLabel('5511777777777', 'label_456', 'remove');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_labels_when_empty()
    {
        $service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->method('get')->willReturn([
            'status' => 'success',
            'labels' => [],
        ]);

        $labelResource = new Label($service, 'test-instance');
        $result        = $labelResource->findLabels();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('labels', $result);
        $this->assertCount(0, $result['labels']);
    }

    /** @test */
    public function it_can_add_label_with_empty_number()
    {
        $result = $this->labelResource->addLabel('', 'label_empty');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_remove_label_with_empty_label_id()
    {
        $result = $this->labelResource->removeLabel('5511777777777', '');

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
            'labels' => [
                [
                    'id'    => 'label-id-123',
                    'name'  => 'Important',
                    'color' => 4,
                ],
            ],
        ]);

        $this->service->method('post')->willReturn([
            'status'  => 'success',
            'message' => 'Label operation successful',
        ]);

        $this->labelResource = new Label($this->service, 'test-instance');
    }
}
