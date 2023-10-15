<?php

namespace Alangiacomin\LaravelBasePack\Tests\Mocks\Examples;

use Illuminate\Queue\Jobs\Job;

class JobExample extends Job
{
    public function __construct(string $queue)
    {
        $this->queue = $queue;
    }

    public function getJobId()
    {
    }

    public function getRawBody()
    {
    }
}
