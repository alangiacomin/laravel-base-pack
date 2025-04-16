<?php

namespace Tests\FakeClasses;

use AlanGiacomin\LaravelBasePack\Controllers\Controller;

class ExampleController extends Controller
{
    public function esegui()
    {
        $this->executeCommand(new ExampleCommand());
    }
}
