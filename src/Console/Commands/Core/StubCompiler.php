<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands\Core;

use Alangiacomin\LaravelBasePack\Exceptions\BasePackException;
use Illuminate\Support\Facades\Config;

/**
 * Compile and save stub files
 */
class StubCompiler
{
    /**
     * Stub name template
     */
    private string $stubName;

    /**
     * Output file name
     */
    private string $outName;

    /**
     * Stub contents
     */
    private string $contents;

    /**
     * Constructor
     *
     * @param  string  $stubName  Stub name template
     * @param  string  $outName   Output file name
     */
    public function __construct(string $stubName, string $outName)
    {
        $this->stubName = $stubName;
        $this->outName = $outName;

        $this->contents = $this->getContents();
    }

    /**
     * Get contents from stub file
     */
    private function getContents(): string
    {
        $stubFile = __DIR__.'/../stubs/php/'.$this->stubName.'.php.stub';

        return file_get_contents($stubFile);
    }

    /**
     * @throws BasePackException
     */
    public static function Compile(string $stubName, string $outName, string $lastNamespace, array $replacements): void
    {
        $compiler = new StubCompiler($stubName, $outName);
        $compiler->multiReplace($replacements);
        $compiler->save($lastNamespace);
    }

    /**
     * @return $this
     */
    public function multiReplace(array $replacements): StubCompiler
    {
        $repl = [];
        foreach ($replacements as $key => $value) {
            $repl[lcfirst($key)] = lcfirst($value);
            $repl[ucfirst($key)] = ucfirst($value);
        }

        $this->contents = preg_replace(
            array_map(fn ($k) => "/\{\{ *{$k} *\}\}/", array_keys($repl)),
            array_values($repl),
            $this->contents
        );

        return $this;
    }

    /**
     * Save new file
     *
     * @param  string  $namespace  Last part namespace where to save file
     *
     * @throws BasePackException Throws if no contents set
     */
    public function save(string $namespace): void
    {
        if (!isset($this->contents)) {
            throw new BasePackException('Contents not set');
        }

        $this->putContents($namespace);
    }

    /**
     * Save contents to file
     *
     * @param  string  $namespace  Last part namespace where to save file
     *
     * @throws BasePackException Throws if file already exists
     */
    private function putContents(string $namespace): void
    {
        $newFile = base_path().'/'.Config::get('basepack.namespaces.'.$namespace).'/'.$this->outName.'.php';

        if (!is_dir(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        if (file_exists($newFile)) {
            throw new BasePackException("File '$newFile' already exists.");
        }

        file_put_contents($newFile, $this->contents);
    }

    /**
     * Replace stub placeholder with value
     *
     * @param  string  $placeholder  Placeholder
     * @param  string  $value        Value
     * @return $this The compiler
     */
    public function replace(string $placeholder, string $value): StubCompiler
    {
        return $this->multiReplace(
            [
                $placeholder => $value,
            ]
        );
    }
}
