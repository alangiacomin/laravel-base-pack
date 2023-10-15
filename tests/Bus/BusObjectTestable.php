<?php

namespace Alangiacomin\LaravelBasePack\Tests\Bus;

use Alangiacomin\LaravelBasePack\Bus\BusObject;
use Alangiacomin\LaravelBasePack\Tests\TestableCallables;

class BusObjectTestable extends BusObject
{
    use TestableCallables;

    public string $first = '';

    public string $second = '';
}
