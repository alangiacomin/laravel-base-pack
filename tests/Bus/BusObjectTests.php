<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\PhpUtils\Guid;
use Exception;

uses()->group('BusObject');

beforeEach(function () {
    $this->obj = new BusObjectTestable();
});

test("constructor new default object", function () {
    expect(Guid::isValid($this->obj->id))->toBeTrue();
    expect(Guid::isValid($this->obj->correlationId))->toBeTrue();
    expect($this->obj->id != $this->obj->correlationId)->toBeTrue();
});

test("constructor must set empty correlation id", function () {
    $this->obj = new BusObjectTestable([
        'correlationId' => '',
    ]);
    expect(Guid::isValid($this->obj->correlationId))->toBeTrue();
});

test("constructor must not replace existing correlation id", function () {
    $this->obj = new BusObjectTestable([
        'correlationId' => 'e36dc4f8-f3e2-4aa5-a544-832a95e95419',
    ]);
    expect($this->obj->correlationId)->toBe('e36dc4f8-f3e2-4aa5-a544-832a95e95419');
});

test("get unknown prop must throw exception", function () {
    $act = fn() => $this->obj->unknownProp;
    expect($act)->toThrow(Exception::class, "Property 'unknownProp' not readable.");
});

test("get known prop must not throw exception", function () {
    $this->obj->correlationId = "b2f805aa-ede9-4b4e-a5d4-5138b1d86840";
    $v = $this->obj->correlationId;
    expect($v)->toBe("b2f805aa-ede9-4b4e-a5d4-5138b1d86840");
});

test("set unknown prop must throw exception", function () {
    $act = fn() => $this->obj->unknownProp = 1;
    expect($act)->toThrow(Exception::class, "Property 'unknownProp' not writeable.");
});

test("set known prop must not throw exception", function () {
    $this->obj->correlationId = "b2f805aa-ede9-4b4e-a5d4-5138b1d86840";
    expect($this->obj->correlationId)->toBe("b2f805aa-ede9-4b4e-a5d4-5138b1d86840");
});

test("clone must have new id", function () {
    $cloned = $this->obj->clone();
    expect($cloned->id)->not->toBe($this->obj->id);
});

test("clone must not replace props", function () {
    $this->obj->correlationId = "b2f805aa-ede9-4b4e-a5d4-5138b1d86840";
    $cloned = $this->obj->clone();
    expect($cloned->correlationId)->toBe("b2f805aa-ede9-4b4e-a5d4-5138b1d86840");
});

test("payload props", function () {
    $this->obj->id = "254470FF-3CA4-435B-B5F1-CFC411DC8369";
    $this->obj->correlationId = "b2f805aa-ede9-4b4e-a5d4-5138b1d86840";
    $payload = $this->obj->payload();
    expect($payload)->toBe('{"id":"254470FF-3CA4-435B-B5F1-CFC411DC8369","correlationId":"b2f805aa-ede9-4b4e-a5d4-5138b1d86840"}');
});

test("name", function () {
    $name = $this->obj->name();
    expect($name)->toBe('BusObjectTestable');
});

test("class", function () {
    $class = $this->obj->class();
    expect($class)->toBe('Alangiacomin\LaravelBasePack\Tests\Bus\BusObjectTestable');
});

test("handlerName", function () {
    $name = $this->obj->handlerName();
    expect($name)->toBe('BusObjectTestableHandler');
});

test("props", function () {
    $this->obj->id = "254470FF-3CA4-435B-B5F1-CFC411DC8369";
    $this->obj->correlationId = "b2f805aa-ede9-4b4e-a5d4-5138b1d86840";
    $props = $this->obj->props();
    expect($props)->toBe([
        'id' => "254470FF-3CA4-435B-B5F1-CFC411DC8369",
        'correlationId' => "b2f805aa-ede9-4b4e-a5d4-5138b1d86840",
    ]);
});

test("assignNewId", function () {
    $this->obj->id = "254470FF-3CA4-435B-B5F1-CFC411DC8369";
    $this->obj->assignNewId();
    expect($this->obj->id)->not->toBe("254470FF-3CA4-435B-B5F1-CFC411DC8369");
});
