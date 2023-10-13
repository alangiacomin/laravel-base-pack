<?php

namespace Alangiacomin\LaravelBasePack\Tests;

use Exception;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function shouldThrowException(callable $func, string $exception, string $message): void
    {
        try {
            $func();
        } catch (Exception $ex) {
            expect(get_class($ex))->toBe($exception);
            expect($ex->getMessage())->toBe($message);
        }
    }

    public function shouldNotThrowException(callable $func): void
    {
        $exception = null;

        try {
            $func();
        } catch (Exception $ex) {
            $exception = $ex;
        }

        expect($exception)->toBeNull();
    }
}
