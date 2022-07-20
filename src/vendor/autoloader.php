<?php

spl_autoload_register(function($className) {
    // Convert the class name from using forward-slashes to whatever the system directory separator
    // is then just append .php and attempt to load the file starting in this directory
    // (the vendor dir)
    $classFilename = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($classFilename)) {
        // Deliberately using require over include in case the file can't be opened
        require $classFilename;
    }
});
