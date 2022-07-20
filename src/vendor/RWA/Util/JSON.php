<?php

namespace RWA\Util;

/**
 * A simple class wrapper for json_encode and json_decode that automatically throws on error and
 * always decodes to an associative array.
 */
class JSON {
    public static function parse(string $json): array {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function stringify(object|array $obj): string {
        return json_encode($obj, JSON_THROW_ON_ERROR);
    }

    public static function prettify(object|array $obj): string {
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
   }
}
