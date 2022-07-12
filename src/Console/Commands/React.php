<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\PhpUtils\DateTime;
use Exception;
use RecursiveDirectoryIterator;
use function PHPUnit\Framework\directoryExists;

/**
 * @method call(string $string)
 */
class React extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:react';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install React';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handleCommand()
    {
        $this->dependencies();
        $this->publish();
        $this->cleanup();

        $this->newLine();
    }

    private function dependencies()
    {
        $this->newLine();
        $this->comment("Install dependencies");

        $deps = [
            '@alangiacomin/js-utils' => '^2.0.4',
            '@alangiacomin/ui-components-react' => '~0.0.3',
            '@reduxjs/toolkit' => '^1.8.3',
            'bootstrap' => '^5.1.3',
            'bootstrap-icons' => '^1.8.3',
            'classnames' => '^2.3.1',
            'prop-types' => '^15.8.1',
            'react' => '^18.2.0',
            'react-dom' => '^18.2.0',
            'react-redux' => '^8.0.2',
            'react-router' => '^6.3.0',
            'react-router-dom' => '^6.3.0',
        ];

        $devDeps = [
            "@vitejs/plugin-react"=> "^1.3.2",
            'eslint' => '^8.19.0',
            'eslint-config-airbnb' => '^19.0.4',
            'eslint-config-react-app' => '^7.0.1',
            'eslint-plugin-babel' => '^5.3.1',
            'eslint-plugin-react' => '^7.30.1',
            'laravel-vite-plugin' => '^0.2.4',
            'sass' => '^1.53.0',
            'vite' => '^2.9.14',
        ];

        $string = file_get_contents(base_path('package.json'));
        $json_a = json_decode($string, true);

        foreach (['devDependencies', 'dependencies'] as $section)
        {
            if (isset($json_a[$section]))
            {
                foreach ($json_a[$section] as $key => $value)
                {
                    if (in_array($key, array_keys($deps)))
                    {
                        unset($json_a[$section][$key]);
                    }
                }
            }
            else
            {
                $json_a[$section] = [];
            }
        }

        $json_a['dependencies'] = [...$json_a['dependencies'], ...$deps];
        $json_a['devDependencies'] = [...$json_a['devDependencies'], ...$devDeps];
        ksort($json_a['dependencies']);
        ksort($json_a['devDependencies']);

        file_put_contents(
            base_path('package.json'),
            json_encode($json_a, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        exec("npm install", $output);
        $this->info(implode(PHP_EOL, $output));

    }

    private function publish()
    {
        $this->newLine();
        $this->comment("Publish vendor files");


        $files = [
            base_path('.eslintrc'),
            base_path('webpack.mix.js'),
            base_path('vite.config.js')
            ];
        foreach ($files as $file)
        {
            if (file_exists($file) && !is_link($file))
            {
                $date = DateTime::now('Ymd.His');
                copy($file, "${file}.${date}.old");
                unlink($file);
            }
        }

        $this->call("vendor:publish", [
            "--provider" => "Alangiacomin\LaravelBasePack\LaravelBasePackServiceProvider",
            "--tag" => "basepack-react",
        ]);
    }

    private function cleanup()
    {
        $this->newLine();
        $this->comment("Cleanup Laravel default files");

        $dir = resource_path('css');
        if (is_dir($dir) &&!is_link($dir))
        {
            array_map('unlink', glob("$dir/*.*"));
            rmdir($dir);
        }

        $files = [
            resource_path('js/app.js'),
            resource_path('js/bootstrap.js'),
        ];
        foreach ($files as $file)
        {
            if (file_exists($file) && !is_link($file))
            {
                unlink($file);
            }
        }
    }
}
