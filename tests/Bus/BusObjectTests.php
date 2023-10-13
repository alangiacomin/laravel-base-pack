<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use Alangiacomin\LaravelBasePack\Exceptions\BusException;
use Alangiacomin\LaravelBasePack\Tests\TestCase;
use Alangiacomin\PhpUtils\Guid;
use stdClass;

class BusObjectTests extends TestCase
{
    public BusObject $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new BusObjectTestable();
    }

    /***************/
    /* CONSTRUCTOR */
    /***************/

    public function test_constructor_new_default_object()
    {
        expect(Guid::isValid($this->obj->id))->toBeTrue();
    }

    public function test_constructor_set_props_from_array()
    {
        $this->obj = new BusObjectTestable(
            [
                'first' => 'this is the first prop',
                'second' => 'this is another prop',
            ]
        );
        expect($this->obj->first)->toBe('this is the first prop');
        expect($this->obj->second)->toBe('this is another prop');
    }

    public function test_constructor_set_props_from_object()
    {
        $props = new stdClass();
        $props->first = 'this is the first prop';
        $props->second = 'this is another prop';
        $this->obj = new BusObjectTestable($props);
        expect($this->obj->first)->toBe('this is the first prop');
        expect($this->obj->second)->toBe('this is another prop');
    }

    /**********/
    /* GETTER */
    /**********/

    public function test_get_unknown_prop_must_throw_exception()
    {
        $this->shouldThrowException(
            fn () => $this->obj->unknownProp,
            BusException::class,
            "Property 'unknownProp' not readable."
        );
    }

    public function test_get_known_prop_must_not_throw_exception()
    {
        $this->obj->id = 'b2f805aa-ede9-4b4e-a5d4-5138b1d86840';
        $v = $this->obj->id;
        expect($v)->toBe('b2f805aa-ede9-4b4e-a5d4-5138b1d86840');
    }

    /**********/
    /* SETTER */
    /**********/

    public function test_set_unknown_prop_must_throw_exception()
    {
        $this->shouldThrowException(
            fn () => $this->obj->unknownProp = 1,
            BusException::class,
            "Property 'unknownProp' not writeable."
        );
    }

    public function test_set_known_prop_must_not_throw_exception()
    {
        $this->obj->id = 'b2f805aa-ede9-4b4e-a5d4-5138b1d86840';
        expect($this->obj->id)->toBe('b2f805aa-ede9-4b4e-a5d4-5138b1d86840');
    }

    /*********/
    /* CLONE */
    /*********/

    public function test_clone_must_have_new_id()
    {
        $cloned = $this->obj->clone();
        expect($cloned->id)->not->toBe($this->obj->id);
    }

    public function test_clone_must_not_replace_props()
    {
        $this->obj->connection = 'connection name';
        $cloned = $this->obj->clone();
        expect($cloned->connection)->toBe('connection name');
    }

    /***********/
    /* PAYLOAD */
    /***********/

    public function test_payload_props()
    {
        $this->obj->id = '254470FF-3CA4-435B-B5F1-CFC411DC8369';
        $payload = $this->obj->payload();
        expect($payload)->toBe(
            '{"id":"254470FF-3CA4-435B-B5F1-CFC411DC8369","connection":"","first":"","second":""}'
        );
    }

    /*********/
    /* PROPS */
    /*********/

    public function test_props()
    {
        $this->obj->id = '254470FF-3CA4-435B-B5F1-CFC411DC8369';
        $props = $this->obj->props();
        expect($props)->toBe(
            [
                'id' => '254470FF-3CA4-435B-B5F1-CFC411DC8369',
                'connection' => '',
                'first' => '',
                'second' => '',
            ]
        );
    }

    /*****************/
    /* ASSIGN NEW ID */
    /*****************/

    public function test_assign_new_id()
    {
        $this->obj->id = '254470FF-3CA4-435B-B5F1-CFC411DC8369';
        $this->obj->assignNewId();
        expect($this->obj->id)->not->toBe('254470FF-3CA4-435B-B5F1-CFC411DC8369');
    }
}
