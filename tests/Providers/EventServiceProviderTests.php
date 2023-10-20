<?php

namespace Alangiacomin\LaravelBasePack\Tests\Providers;

use Alangiacomin\LaravelBasePack\Tests\TestCase;
use Alangiacomin\PhpUtils\Path;
use Illuminate\Contracts\Foundation\Application;
use Mockery\MockInterface;

class EventServiceProviderTests extends TestCase
{
    public EventServiceProviderTestable $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $appMock = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('path')
                ->withAnyArgs()
                ->andReturn('myPath');
        });

        $this->provider = new EventServiceProviderTestable(new \Illuminate\Foundation\Application('myBasePath'));
    }

    /********/
    /* BOOT */
    /********/

    public function test_boot_ok()
    {
        $this->shouldNotThrowException(
            fn () => $this->provider->boot()
        );
    }

    /**************************/
    /* SHOULD DISCOVER EVENTS */
    /**************************/

    public function test_shouldDiscoverEvents_true()
    {
        // Arrange

        // Act
        $ret = $this->provider->shouldDiscoverEvents();

        expect($ret)->toBeTrue();
    }

    /**************************/
    /* DISCOVER EVENTS WITHIN */
    /**************************/

    public function test_discoverEventsWithin_ok()
    {
        // Arrange

        // Act
        $ret = $this->provider->discoverEventsWithin();

        expect($ret)->toBe([Path::combine('myBasePath', 'app', 'Events')]);
    }
}
