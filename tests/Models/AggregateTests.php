<?php

namespace Alangiacomin\LaravelBasePack\Tests\Models;

use Alangiacomin\LaravelBasePack\Tests\Mocks\AggregateMock;
use Alangiacomin\LaravelBasePack\Tests\Mocks\EventMock;

uses()->group('Aggregate');

beforeEach(function () {
    $this->aggregate = new AggregateMock();
});

/* RAISE */

test("raise 1 event", function () {
    // Arrange
    $event = new EventMock();
    $event->prop = 'first event';

    // Act
    $this->aggregate->raise($event);

    // Assert
    expect($this->aggregate->events)->toHaveCount(1);
    expect($this->aggregate->events)->toContain($event);
});

test("raise 2 events", function () {
    $event = new EventMock();
    $event->prop = 'first event';
    $event2 = new EventMock();
    $event2->prop = 'second event';
    $this->aggregate->raise($event);
    $this->aggregate->raise($event2);
    expect($this->aggregate->events)->toHaveCount(2);
    expect($this->aggregate->events)->toContain($event);
    expect($this->aggregate->events)->toContain($event2);
});
