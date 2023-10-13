<?php

namespace Alangiacomin\LaravelBasePack\Core;

/**
 * Utilities for reflection and bindings
 */
class ConversionUtility
{
    public static function toArray(object|array $obj): array
    {
        if (is_object($obj)) {
            return get_object_vars($obj);
        }

        return $obj;
    }
}
