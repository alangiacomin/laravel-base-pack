<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\PhpUtils\Guid;
use Exception;
use JetBrains\PhpStorm\Pure;

abstract class BusObject implements IBusObject
{
    /**
     * Bus object id
     *
     * @var string
     */
    public string $id;

    /**
     * Id for bus object correlation
     *
     * @var string
     */
    public string $correlationId;

    /**
     * Connection name, required by Laravel
     *
     * @var string
     */
    public string $connection;

    /**
     * Object constructor setting {@see id} and {@see correlationId}
     *
     * @param $props
     */
    public function __construct($props = null)
    {
        $this->id = "";
        $this->correlationId = "";

        if (isset($props))
        {
            if (is_object($props))
            {
                $props = get_object_vars($props);
            }
            foreach ($props as $key => $value)
            {
                $this->$key = $value;
            }
        }

        $this->assignNewId();

        if (!isset($this->correlationId) || $this->correlationId == "")
        {
            $this->correlationId = Guid::newGuid();
        }
    }

    /**
     * Getter for parent properties
     *
     * @throws Exception
     */
    public function __get($name)
    {
        throw new Exception("Property '$name' not readable.");
    }

    /**
     * Setter for parent properties
     *
     * @throws Exception
     */
    public function __set($name, $value)
    {
        throw new Exception("Property '$name' not writeable.");
    }

    /**
     * @inheritDoc
     */
    final public function clone(): self
    {
        $clone = clone $this;
        $clone->assignNewId();
        return $clone;
    }

    /**
     * @inheritDoc
     */
    final public function payload(): string
    {
        return json_encode($this->props());
    }

    /**
     * @inheritDoc
     */
    #[Pure] final public function name(): string
    {
        $classNameWithNamespace = $this->class();
        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, "\\") + 1);
    }

    /**
     * @inheritDoc
     */
    final public function class(): string
    {
        return get_class($this);
    }

    /**
     * @inheritDoc
     */
    #[Pure] final public function handlerName(): string
    {
        return $this->name()."Handler";
    }

    /**
     * @inheritDoc
     */
    final public function props(): array
    {
        return get_object_vars($this);
    }

    /**
     * Sets {@see id}
     *
     * @return void
     */
    private function assignNewId(): void
    {
        $this->id = Guid::newGuid();
    }
}
