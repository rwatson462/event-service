<?php

namespace RWA\Util;

/**
 * base64_encode and base64_decode functions that are "url safe",
 * that is - the data has '+' replaced with '-' and '/' replaced with '_' after the base64 operation
 * takes place
 */

class Base64Url
{
   public static function encode(string $string): string
   {
      return strtr(base64_encode($string), '+/', '-_');
   }

   public static function decode(string $string): string
   {
      return base64_decode(strtr($string, '-_', '+/'));
   }
}