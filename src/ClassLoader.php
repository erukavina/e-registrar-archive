<?php
spl_autoload_register(function ($class) {
    // Base directory where classes are located
    $baseDir = __DIR__ . '/';

    // PSR-4 Namespace Prefix
    $prefix = ''; // adjust according to your namespace convention

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relativeClass = substr($class, $len);

    // Convert namespace separators (\) to directory separators (/)
    $classFilePath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);

    // Construct full path to the class file
    $filePath = $baseDir . $classFilePath . '.php';

    // Check if the file exists
    if (file_exists($filePath)) {
        // Include the class file
        require_once $filePath;
    } else {
        // Class file not found, you might want to handle this gracefully
        // For example, throw an exception or log an error
        throw new Exception("Class file not found: $filePath");
    }
});