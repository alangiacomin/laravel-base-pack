<?php

namespace Tests\Core;

use AlanGiacomin\LaravelBasePack\Core\ClassUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ClassUtilityTest extends TestCase
{
    /*****************/
    /* fullClassName */
    /*****************/

    public static function data_fullClassName(): array
    {
        return [
            'root' => ['ActionDone.php', 'App\ActionDone'],
            'folder' => ['Events/ActionDone.php', 'App\Events\ActionDone'],
            'subfolder' => ['Events/Action/ActionDone.php', 'App\Events\Action\ActionDone'],
        ];
    }

    #[DataProvider('data_fullClassName')]
    #[TestDox('fullClassName(..)')]
    public function test_fullClassName(string $relativePath, string $expected)
    {
        $ret = ClassUtility::fullClassName($this->app_path_os($relativePath));

        $this->assertSame($expected, $ret);
    }

    /*********************/
    /* relativeNamespace */
    /*********************/

    public static function data_relativeNamespace(): array
    {
        return [
            'root' => ['ActionDone.php', ''],
            'folder' => ['Events/ActionDone.php', '\Events'],
            'subfolder' => ['Events/Action/ActionDone.php', '\Events\Action'],
        ];
    }

    #[DataProvider('data_relativeNamespace')]
    #[TestDox('relativeNamespace(..)')]
    public function test_relativeNamespace(string $relativePath, string $expected)
    {
        $ret = ClassUtility::relativeNamespace($this->app_path_os($relativePath));

        $this->assertSame($expected, $ret);
    }

    /****************************/
    /* filenameWithoutExtension */
    /****************************/

    public static function data_filenameWithoutExtension(): array
    {
        return [
            'root' => ['ActionDone.php', 'ActionDone'],
            'folder' => ['Events/ActionDone.php', 'ActionDone'],
            'subfolder' => ['Events/Action/ActionDone.php', 'ActionDone'],
        ];
    }

    #[DataProvider('data_filenameWithoutExtension')]
    #[TestDox('filenameWithoutExtension(..)')]
    public function test_filenameWithoutExtension(string $relativePath, string $expected)
    {
        $ret = ClassUtility::filenameWithoutExtension($this->app_path_os($relativePath));

        $this->assertSame($expected, $ret);
    }

    /*********************/
    /* adjustBackslashes */
    /*********************/

    public static function data_adjustBackslashes(): array
    {
        return [
            'Windows' => ['Events\ActionDone', 'Events\ActionDone'],
            'Linux' => ['Events/ActionDone', 'Events\ActionDone'],
        ];
    }

    #[DataProvider('data_adjustBackslashes')]
    #[TestDox('adjustBackslashes(..)')]
    public function test_adjustBackslashes(string $string, string $expected)
    {
        $ret = ClassUtility::adjustBackslashes($string);

        $this->assertSame($expected, $ret);
    }
}
