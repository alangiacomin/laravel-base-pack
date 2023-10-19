<?php

namespace Alangiacomin\LaravelBasePack\Tests;

use Alangiacomin\LaravelBasePack\LaravelBasePack;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\DummyExample;
use Illuminate\Http\Request;

class LaravelBasePackTests extends TestCase
{
    public LaravelBasePack $basePack;

    /******************************/
    /* CALL STATIC WITH INJECTION */
    /******************************/

    public function test_callStaticWithInjection_no_params()
    {
        // Arrange
        $this->basePack = new LaravelBasePack();

        $this->basePack->callStaticWithInjection(DummyExample::class, 'staticWithoutParams');

        expect(DummyExample::$staticRequest)->toBeInstanceOf(Request::class);
    }

    public function test_callStaticWithInjection_params()
    {
        // Arrange
        $this->basePack = new LaravelBasePack();

        // Act
        $this->basePack->callStaticWithInjection(
            DummyExample::class,
            'staticWithParams',
            ['param' => 'parameter']);


        expect(DummyExample::$staticRequest)->toBeInstanceOf(Request::class);
    }

    /***********************/
    /* CALL WITH INJECTION */
    /***********************/

    public function test_callWithInjection_no_params()
    {
        // Arrange
        $this->basePack = new LaravelBasePack();
        $class = new DummyExample();

        $this->basePack->callWithInjection($class, 'withoutParams');

        expect($class->instanceRequest)->toBeInstanceOf(Request::class);
    }

    public function test_callWithInjection_params()
    {
        // Arrange
        $this->basePack = new LaravelBasePack();
        $class = new DummyExample();

        // Act
        $this->basePack->callWithInjection(
            $class,
            'withParams',
            ['param' => 'parameter']);

        expect($class->instanceRequest)->toBeInstanceOf(Request::class);
    }

    /*********************/
    /* INJECTED INSTANCE */
    /*********************/

    public function test_injectedInstance()
    {
        // Arrange
        $this->basePack = new LaravelBasePack();

        // Act
        $ret = $this->basePack->injectedInstance(DummyExample::class);

        expect($ret)->toBeInstanceOf(DummyExample::class);
    }
}
