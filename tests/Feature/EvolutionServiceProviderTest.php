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
        $app['config']->set('evolution.default_instance', 'default');
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
        $this->assertEquals('default', config('evolution.default_instance'));
    }

    /** @test */
    public function the_facade_works()
    {
        $this->assertInstanceOf(EvolutionApiClient::class, Evolution::getFacadeRoot());
    }
}
