<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On:  18-Feb-2024                              |
| Updated On:  05-Nov-2025  ChatGPT                     |
+-------------------------------------------------------+
*/

namespace BadDragon;

class Controller
{
    public function __construct()
    {
        // Base controller initialization logic (optional)
    }

    /**
     * Resolve the controller file paths for a given route
     *
     * @param object $route Expected to have module, controller, and method properties
     * @return array Array of resolved controller paths
     */
    public function fire(object $route): array
    {
        if (!defined('BD')) {
            throw new \RuntimeException('Base directory constant BD is not defined.');
        }

        $basePath = rtrim(BD, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR;
        $moduleDir = $basePath . $route->module . DIRECTORY_SEPARATOR;
        $controllerDir = $moduleDir . $route->controller . DIRECTORY_SEPARATOR;

        $controllerModule = $moduleDir . $route->module . '.php';
        $controllerClass  = $controllerDir . $route->controller . '.php';
        $controllerScript = $controllerDir . $route->method . '.php';

        $files = [
            'Module'   => $controllerModule,
            'Controller' => $controllerClass,
            'Script'   => $controllerScript
        ];

        foreach ($files as $type => $path) {
            if (!is_file($path)) {
                http_response_code(500);
                die("Missing {$type} file: {$path}");
            }
        }

        return array_values($files);
    }
}
