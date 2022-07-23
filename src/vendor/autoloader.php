<?php

/**
 * The autoloader presented here is bare-bones.  It offers very little protection if something
 * goes wrong!   As per the PSR-4 autoloading standard, this function shouldn't throw any
 * exceptions, it should fail silently.  PHP itself will then fail to find the class being
 * requested which is where the debugging exercise can start.
 * In a change to PSR-4, this function assumes any classes it's looking for start in this directory.
 */

spl_autoload_register(function($className) {
    $classFilename = __DIR__ 
        . DIRECTORY_SEPARATOR
        . str_replace('\\', DIRECTORY_SEPARATOR, $className)
        . '.php';
    if (file_exists($classFilename)) {
        @include $classFilename;
    }
});
