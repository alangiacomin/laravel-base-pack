<?php

namespace Alangiacomin\LaravelBasePack\Bus;

use Alangiacomin\LaravelBasePack\Core\ConversionUtility;
use Alangiacomin\LaravelBasePack\Exceptions\BusException;
use Alangiacomin\PhpUtils\Guid;
use Exception;

abstract class BusObject implements IBusObject
{
    /**
     * Bus object id
     */
    public string $id = '';

    /**
     * Connection name, required by Laravel
     */
    public string $connection = '';

    /**
     * Object constructor setting {@see id}
     */
    public function __construct(array|object $props = null)
    {
        if (isset($props)) {
            $props = ConversionUtility::toArray($props);
            foreach ($props as $key => $value) {
                $this->$key = $value;
            }
        }

        $this->assignNewId();
    }

    /**
     * Getter for parent properties
     *
     * @throws Exception
     */
    public function __get($name)
    {
        throw new BusException("Property '$name' not readable.");
    }

    /**
     * Setter for parent properties
     *
     * @throws Exception
     */
    public function __set($name, $value)
    {
        throw new BusException("Property '$name' not writeable.");
    }

    /**
     * {@inheritDoc}
     */
    final public function clone(): self
    {
        $clone = clone $this;
        $clone->assignNewId();

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    final public function payload(): string
    {
        return json_encode($this->props());
    }

    /**
     * {@inheritDoc}
     */
    final public function props(): array
    {
        return get_object_vars($this);
    }

    /**
     * Sets a new {@see id}
     */
    private function assignNewId(): void
    {
        $this->id = Guid::newGuid();
    }

    /**
     * {@inheritDoc}
     */
    final public function fullName(): string
    {
        return get_class($this);
    }
}
