<?php

namespace Alangiacomin\LaravelBasePack\Bus;

interface IBusObject
{
    /**
     * Gets bus object name
     *
     * @return string
     */
    function name(): string;

    /**
     * Gets bus object handler name
     *
     * @return string
     */
    function handlerName(): string;

    /**
     * Gets bus object class name
     *
     * @return string
     */
    function class(): string;

    /**
     * Gets props list
     *
     * @return array
     */
    function props(): array;

    /**
     * Encodes props as json string
     *
     * @return string
     */
    function payload(): string;

    /**
     * Clone the object with a new {@see id}
     *
     * @return BusObject
     */
    function clone(): self;

}
