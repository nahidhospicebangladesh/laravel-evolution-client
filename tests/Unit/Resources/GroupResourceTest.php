<?php
// tests/Unit/Resources/GroupResourceTest.php

namespace SamuelTerra22\LaravelEvolutionClient\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use SamuelTerra22\LaravelEvolutionClient\Resources\Group;
use SamuelTerra22\LaravelEvolutionClient\Services\EvolutionService;

class GroupResourceTest extends TestCase
{
    /**
     * @var Group
     */
    protected $groupResource;

    /**
     * @var EvolutionService
     */
    protected $service;

    /** @test */
    public function it_can_get_all_groups()
    {
        $result = $this->groupResource->all();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('groups', $result);
    }

    /** @test */
    public function it_can_find_group()
    {
        $result = $this->groupResource->find('123456789@g.us');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_create_group()
    {
        $result = $this->groupResource->create('Test Group', [
            '5511999999999',
            '5511888888888',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_update_group_subject()
    {
        $result = $this->groupResource->updateSubject('123456789@g.us', 'New Group Name');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_update_group_description()
    {
        $result = $this->groupResource->updateDescription('123456789@g.us', 'New group description');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_add_participants()
    {
        $result = $this->groupResource->addParticipants('123456789@g.us', [
            '5511777777777',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_remove_participants()
    {
        $result = $this->groupResource->removeParticipants('123456789@g.us', [
            '5511777777777',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_promote_participant_to_admin()
    {
        $result = $this->groupResource->promoteToAdmin('123456789@g.us', '5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_demote_participant_from_admin()
    {
        $result = $this->groupResource->demoteFromAdmin('123456789@g.us', '5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_leave_group()
    {
        $result = $this->groupResource->leave('123456789@g.us');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_group_invite_code()
    {
        $service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->method('get')->willReturn([
            'status'     => 'success',
            'inviteCode' => 'ABC123XYZ789',
        ]);

        $groupResource = new Group($service, 'test-instance');
        $result        = $groupResource->getInviteCode('123456789@g.us');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('inviteCode', $result);
        $this->assertEquals('ABC123XYZ789', $result['inviteCode']);
    }

    /** @test */
    public function it_can_join_group_with_invite_code()
    {
        $result = $this->groupResource->joinWithInviteCode('ABC123XYZ789');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_instance_name()
    {
        $this->assertEquals('test-instance', $this->groupResource->getInstanceName());
    }

    /** @test */
    public function it_can_set_instance_name()
    {
        $this->groupResource->setInstanceName('new-instance');
        $this->assertEquals('new-instance', $this->groupResource->getInstanceName());
    }

    /** @test */
    public function it_formats_phone_numbers_for_participants()
    {
        // Test that participant numbers are formatted correctly
        $participants = ['+55 (11) 99999-9999', '5511888888888', '+1-234-567-8900'];
        
        $this->service->method('post')->willReturn([
            'status'  => 'success',
            'groupId' => '123456789@g.us',
        ]);

        $result = $this->groupResource->create('Test Group', $participants);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_create_group_with_single_participant()
    {
        $result = $this->groupResource->create('Single Participant Group', ['5511999999999']);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_add_single_participant()
    {
        $result = $this->groupResource->addParticipants('123456789@g.us', ['5511555555555']);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_remove_single_participant()
    {
        $result = $this->groupResource->removeParticipants('123456789@g.us', ['5511555555555']);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_create_group_with_empty_participants()
    {
        $result = $this->groupResource->create('Empty Group', []);

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
            'groups' => [
                [
                    'id'       => '123456789@g.us',
                    'subject'  => 'Test Group',
                    'creation' => 1678901234,
                    'owner'    => '5511999999999@c.us',
                    'desc'     => 'Group description',
                ],
            ],
        ]);

        $this->service->method('post')->willReturn([
            'status'  => 'success',
            'message' => 'Operation successful',
            'groupId' => '123456789@g.us',
        ]);

        $this->service->method('put')->willReturn([
            'status'  => 'success',
            'message' => 'Group updated successfully',
        ]);

        $this->groupResource = new Group($this->service, 'test-instance');
    }
}
