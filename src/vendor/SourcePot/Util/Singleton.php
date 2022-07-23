<?php

namespace SourcePot\Util;

trait Singleton {
   // prefix with underscore to prevent clashing with implementing class
   private static ?self $_instance = null;

   public static function getInstance(): self {
      if(self::$_instance !== null) return self::$_instance;
      self::$_instance = new self;
      return self::$_instance;
   }
}