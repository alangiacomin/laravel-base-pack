<?php

namespace Alangiacomin\LaravelBasePack\Tests\Event;

use Alangiacomin\LaravelBasePack\Events\Event;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\EventExample;
use Alangiacomin\LaravelBasePack\Tests\TestCase;

class EventTests extends TestCase
{
    public Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = new EventExample();
    }

    /****************/
    /* BROADCAST AS */
    /****************/

    public function test_broadcast_name()
    {
        $ret = $this->event->broadcastAs();

        expect($ret)->toBe('EventExample');
    }
}
