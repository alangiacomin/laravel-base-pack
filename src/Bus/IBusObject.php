<?php

namespace Alangiacomin\LaravelBasePack\Bus;

interface IBusObject
{
    /**
     * Clone the object with a new {@see id}
     *
     * @return BusObject
     */
    public function clone(): self;

    /**
     * Encodes props as json string
     */
    public function payload(): string;

    /**
     * Gets props list
     */
    public function props(): array;
}
