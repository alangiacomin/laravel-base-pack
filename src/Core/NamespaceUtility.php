<?php

namespace Alangiacomin\LaravelBasePack\Core;

use Illuminate\Support\Arr;

/**
 * Utilities for namespaces
 */
class NamespaceUtility
{
    /**
     * Gets the element name
     *
     * @param  string  $namespace Element full name
     * @return string Element name
     */
    public static function elementName(string $namespace): string
    {
        $normalized = self::normalizeNamespaceSlash($namespace);

        return Arr::last(explode('\\', $normalized));
    }

    /**
     * Returns namespace with backslashes
     *
     * @param  string  $text Namespace
     * @return string Normalized namespace
     */
    public static function normalizeNamespaceSlash(string $text): string
    {
        return str_replace('/', '\\', $text);
    }

    /**
     * Returns path with slashes
     *
     * @param  string  $text Path
     * @return string Normalized path
     */
    public static function normalizePathSlash(string $text): string
    {
        return str_replace('\\', '/', $text);
    }

    /**
     * Gets namespace from element full name
     *
     * @param  string  $namespace Element full name
     * @return string Namespace
     */
    public static function relativeNamespace(string $namespace): string
    {
        $normalized = self::normalizeNamespaceSlash($namespace);
        $relative = implode('\\', array_slice(explode('\\', $normalized), 0, -1));

        return empty($relative)
            ? ''
            : "\\$relative";
    }
}
