<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On:  19-Feb-2024                              |
| Updated On:  05-Nov-2025 ChatGPT                      |
+-------------------------------------------------------+
*/

define('BADDRAGON', 'Ver 1.0.0');

$classmap = [
    'BadDragon' => __DIR__ . '/App',
];

spl_autoload_register(static function (string $classname) use ($classmap): void {
    $parts = explode('\\', $classname);
    $namespace = array_shift($parts);
    $classfile = array_pop($parts) . '.php';

    // Namespace not mapped — skip
    if (!isset($classmap[$namespace])) {
        return;
    }

    $baseDir = rtrim($classmap[$namespace], DIRECTORY_SEPARATOR);
    $subPath = $parts ? DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) : '';
    $file = $baseDir . $subPath . DIRECTORY_SEPARATOR . $classfile;

    if (is_file($file)) {
        require_once $file;
    }
});
