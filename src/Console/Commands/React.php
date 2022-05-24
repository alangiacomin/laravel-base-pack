<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Alangiacomin\LaravelBasePack\Console\Commands\Core\Command;
use Alangiacomin\PhpUtils\DateTime;
use Exception;

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


        $this->newLine();
    }

    private function dependencies()
    {
        $this->newLine();
        $this->comment("Install dependencies");

        $deps = [
            '@alangiacomin/js-utils' => '^2.0.3',
            '@alangiacomin/ui-components-react' => '^0.0.1',
            '@reduxjs/toolkit' => '^1.8.1',
            'axios' => '^0.27.2',
            'bootstrap' => '^5.1.3',
            'bootstrap-icons' => '^1.8.1',
            'classnames' => '^2.3.1',
            'prop-types' => '^15.8.1',
            'react' => '^18.0.0',
            'react-dom' => '^18.0.0',
            'react-redux' => '^8.0.1',
            'react-router' => '^6.3.0',
            'react-router-dom' => '^6.3.0',
        ];

        $devDeps = [
            '@babel/preset-react' => '^7.16.7',
            'eslint' => '^8.14.0',
            'eslint-config-airbnb' => '^19.0.4',
            'eslint-config-react-app' => '^7.0.1',
            'eslint-plugin-babel' => '^5.3.1',
            'eslint-plugin-react' => '^7.29.4',
            'laravel-mix' => '^6.0.43',
            'resolve-url-loader' => '^5.0.0',
            'sass' => '^1.50.1',
            'sass-loader' => '^12.6.0',
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

        if (file_exists(base_path('.eslintrc')))
        {
            $date = DateTime::now('Ymd.His');
            copy(base_path('.eslintrc'), base_path(".eslintrc.${date}.old"));
            unlink(base_path('.eslintrc'));
        }

        if (file_exists(base_path('webpack.mix.js')))
        {
            $date = DateTime::now('Ymd.His');
            copy(base_path('webpack.mix.js'), base_path("webpack.mix.js.${date}.old"));
            unlink(base_path('webpack.mix.js'));
        }

        $this->call("vendor:publish", [
            "--provider" => "Alangiacomin\LaravelBasePack\LaravelBasePackServiceProvider",
            "--tag" => "basepack-react",
        ]);
    }
}
