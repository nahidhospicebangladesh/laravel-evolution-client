<?php

namespace SamuelTerra22\EvolutionLaravelClient\Tests\Feature;

use Orchestra\Testbench\TestCase;
use SamuelTerra22\EvolutionLaravelClient\EvolutionApiClient;
use SamuelTerra22\EvolutionLaravelClient\EvolutionServiceProvider;
use SamuelTerra22\EvolutionLaravelClient\Facades\Evolution;

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
        $this->assertInstanceOf(\SamuelTerra22\EvolutionLaravelClient\EvolutionApiClient::class, app('evolution'));
    }

    /** @test */
    public function the_facade_provides_access_to_all_resources()
    {
        $client = app('evolution');
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Chat', $client->chat);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Group', $client->group);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Message', $client->message);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Instance', $client->instance);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Call', $client->call);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Label', $client->label);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\Profile', $client->profile);
        $this->assertInstanceOf('SamuelTerra22\EvolutionLaravelClient\Resources\WebSocket', $client->websocket);
    }


    /** @test */
    public function it_publishes_the_config()
    {
        $this->artisan('vendor:publish', [
            '--provider' => 'SamuelTerra22\EvolutionLaravelClient\EvolutionServiceProvider',
            '--tag'      => 'evolution-config'
        ]);

        $this->assertFileExists(config_path('evolution.php'));
    }
}
