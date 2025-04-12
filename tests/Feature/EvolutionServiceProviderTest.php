<?php

namespace SamuelTerra22\EvolutionLaravelClient\Tests\Feature;

use Orchestra\Testbench\TestCase;
use SamuelTerra22\EvolutionLaravelClient\EvolutionApiClient;
use SamuelTerra22\EvolutionLaravelClient\EvolutionServiceProvider;
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;
use SamuelTerra22\EvolutionLaravelClient\Resources\Call;
use SamuelTerra22\EvolutionLaravelClient\Resources\Chat;
use SamuelTerra22\EvolutionLaravelClient\Resources\Group;
use SamuelTerra22\EvolutionLaravelClient\Resources\Instance;
use SamuelTerra22\EvolutionLaravelClient\Resources\Label;
use SamuelTerra22\EvolutionLaravelClient\Resources\Message;
use SamuelTerra22\EvolutionLaravelClient\Resources\Profile;
use SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket;

class EvolutionServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EvolutionServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Evolution' => Evolution::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Configurações para testes
        $app['config']->set('evolution.base_url', 'http://localhost:8080');
        $app['config']->set('evolution.api_key', 'test-api-key');
        $app['config']->set('evolution.default_instance', 'test-instance');
    }

    /** @test */
    public function it_registers_the_service()
    {
        $this->assertTrue($this->app->bound('evolution'));
        $this->assertInstanceOf(EvolutionApiClient::class, $this->app->make('evolution'));
    }

    /** @test */
    public function it_loads_the_config()
    {
        $this->assertEquals('http://localhost:8080', config('evolution.base_url'));
        $this->assertEquals('test-api-key', config('evolution.api_key'));
        $this->assertEquals('test-instance', config('evolution.default_instance'));
    }

    /** @test */
    public function the_facade_works()
    {
        $this->assertInstanceOf(EvolutionApiClient::class, Evolution::getFacadeRoot());
    }

    /** @test */
    public function the_facade_provides_access_to_all_resources()
    {
        $this->assertInstanceOf(Chat::class, Evolution::chat);
        $this->assertInstanceOf(Group::class, Evolution::group);
        $this->assertInstanceOf(Message::class, Evolution::message);
        $this->assertInstanceOf(Instance::class, Evolution::instance);
        $this->assertInstanceOf(Call::class, Evolution::call);
        $this->assertInstanceOf(Label::class, Evolution::label);
        $this->assertInstanceOf(Profile::class, Evolution::profile);
        $this->assertInstanceOf(WebSocket::class, Evolution::websocket);
    }

    /** @test */
    public function it_publishes_the_config()
    {
        $this->artisan('vendor:publish', [
            '--provider' => 'SamuelTerra22\EvolutionLaravelClient\EvolutionServiceProvider',
            '--tag' => 'evolution-config'
        ]);

        $this->assertFileExists(config_path('evolution.php'));
    }
}
