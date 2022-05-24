<?php

namespace Alangiacomin\LaravelBasePack\Console\Commands\Core;

use Exception;
use Illuminate\Support\Facades\Config;

/**
 * Compile and save stub files
 */
class StubCompiler
{
    /**
     * Stub name template
     *
     * @var string
     */
    private string $stubName;

    /**
     * Output file name
     *
     * @var string
     */
    private string $outName;

    /**
     * Stub contents
     *
     * @var string
     */
    private string $contents;

    /**
     * Constructor
     *
     * @param  string  $stubName  Stub name template
     * @param  string  $outName  Output file name
     */
    public function __construct(string $stubName, string $outName)
    {
        $this->stubName = $stubName;
        $this->outName = $outName;

        $this->contents = $this->getContents();
    }

    /**
     * Replace stub placeholder with value
     *
     * @param  string  $placeholder  Placeholder
     * @param  string  $value  Value
     * @return $this The compiler
     */
    public function replace(string $placeholder, string $value): StubCompiler
    {
        $chunks = explode("{{ ".$placeholder." }}", $this->contents);
        $this->contents = implode($value, $chunks);

        return $this;
    }

    /**
     * Save new file
     *
     * @param  string  $namespace Last part namespace where to save file
     * @throws Exception Throws if no contents set
     */
    public function save(string $namespace)
    {
        if (!isset($this->contents))
        {
            throw new Exception("Contents not set");
        }

        $this->putContents($namespace);

    }

    /**
     * Get contents from stub file
     *
     * @return string
     */
    private function getContents(): string
    {
        $stubFile = __DIR__."\\..\\stubs\\php\\".$this->stubName.".php.stub";
        return file_get_contents($stubFile);
    }

    /**
     * Save contents to file
     *
     * @param  string  $namespace Last part namespace where to save file
     * @throws Exception Throws if file already exists
     */
    private function putContents(string $namespace)
    {
        $newFile = base_path()."\\".Config::get("basepack.namespaces.".$namespace)."\\".$this->outName.".php";

        if (!is_dir(dirname($newFile)))
        {
            mkdir(dirname($newFile), 0777, true);
        }

        if (file_exists($newFile))
        {
            throw new Exception("File '$newFile' already exists.");
        }

        file_put_contents($newFile, $this->contents);
    }
}
