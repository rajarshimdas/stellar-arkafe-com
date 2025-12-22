<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On:  29-Jan-2024                              |
| Updated On:  05-Nov-2025  ChatGPT                     |
+-------------------------------------------------------+
*/

namespace BadDragon;

use BadDragon\Controller;

class Router extends Controller
{
    public string $a = '';
    public ?string $module = null;
    public ?string $controller = null;
    public ?string $method = null;
    public array $parts = [];

    public function __construct()
    {
        $this->a = $this->getActionString();

        if ($this->a === '') {
            // $this->handleInvalidRoute('Empty route detected.');
            $this->a = 'login';
        }

        $delimiter = ($_SERVER['REQUEST_METHOD'] === 'POST') ? '-' : '/';
        $this->autoroute($delimiter);
    }

    /**
     * Determine the action string based on request type
     */
    private function getActionString(): string
    {
        if (!empty($_POST['a'])) {
            return trim($_POST['a']);
        }

        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        return $uri ?: '';
    }

    /**
     * Auto-route parsing logic
     */
    private function autoroute(string $delimiter): void
    {
        $parts = array_filter(explode($delimiter, $this->a));
        $this->parts = array_values($parts);

        if (count($this->parts) < 3) {
            $this->redirectOrFail('Incomplete routing info');
        }

        [$module, $controller, $method] = array_slice($this->parts, 0, 3);
        $this->module = ucfirst($module);
        $this->controller = ucfirst($controller);
        $this->method = $method;
    }

    /**
     * Handle invalid or incomplete routes
     */
    private function redirectOrFail(string $message): void
    {
        if (defined('BASE_URL')) {
            header('Location: ' . rtrim(BASE_URL, '/') . '/studio/home.cgi');
        } else {
            http_response_code(404);
            echo "404 - {$message}";
        }
        exit;
    }

    /**
     * Handle unexpected empty routes or missing data
     */
    private function handleInvalidRoute(string $message): void
    {
        http_response_code(400);
        echo "Bad Request - {$message}";
        exit;
    }
}
