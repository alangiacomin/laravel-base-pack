<?php

namespace Alangiacomin\LaravelBasePack\Tests\Controllers;

use Alangiacomin\LaravelBasePack\Bus\Bus;
use Alangiacomin\LaravelBasePack\Commands\CommandResult;
use Alangiacomin\LaravelBasePack\Controllers\Controller;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Alangiacomin\LaravelBasePack\Tests\Mocks\Examples\CommandExample;
use Alangiacomin\LaravelBasePack\Tests\TestCase;
use stdClass;

class ControllerTests extends TestCase
{
    public Controller $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ControllerTestable();
    }

    /***************/
    /* CONSTRUCTOR */
    /***************/

    public function test_controller_constructor_default()
    {
        $this->shouldNotThrowException(fn () => new ControllerTestable());
    }

    /***********/
    /* EXECUTE */
    /***********/

    public function test_controller_execute_default()
    {
        $command = new CommandExample();
        LaravelBasePackFacade::shouldReceive('callStaticWithInjection')->with(
            Bus::class,
            'executeCommand',
            ['command' => $command]
        )->andReturn(
            new CommandResult()
        )->once();

        $this->controller->execute($command);
    }

    /********/
    /* SEND */
    /********/

    public function test_controller_send_default()
    {
        $command = new CommandExample();
        LaravelBasePackFacade::shouldReceive('callStaticWithInjection')->with(
            Bus::class,
            'sendCommand',
            ['command' => $command]
        )->once();

        $this->controller->send($command);
    }

    /*****************/
    /* JSON RESPONSE */
    /*****************/

    public function test_controller_jsonResponse_default()
    {
        $result = new CommandResult();

        $json = $this->controller->jsonResponse($result);

        expect($json->getContent())->toBe('{"success":true,"result":{},"errors":[]}');
    }

    public function test_controller_jsonResponse_success_string()
    {
        $result = new CommandResult();
        $result->setSuccess('OK');

        $json = $this->controller->jsonResponse($result);

        expect($json->getContent())->toBe('{"success":true,"result":"OK","errors":[]}');
    }

    public function test_controller_jsonResponse_success_object()
    {
        $obj = new stdClass();
        $obj->status = 'OK';
        $result = new CommandResult();
        $result->setSuccess($obj);

        $json = $this->controller->jsonResponse($result);

        expect($json->getContent())->toBe('{"success":true,"result":{"status":"OK"},"errors":[]}');
    }

    public function test_controller_jsonResponse_error_string()
    {
        $result = new CommandResult();
        $result->setFailure('KO');

        $json = $this->controller->jsonResponse($result);

        expect($json->getContent())->toBe('{"success":false,"result":{},"errors":["KO"]}');
    }

    public function test_controller_jsonResponse_error_array()
    {
        $arr = ['KO', 'Multiple', 'errors'];
        $result = new CommandResult();
        $result->setFailure($arr);

        $json = $this->controller->jsonResponse($result);

        expect($json->getContent())->toBe('{"success":false,"result":{},"errors":["KO","Multiple","errors"]}');
    }
}
