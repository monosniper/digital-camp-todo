<?php

namespace App\Core;

class View
{
    public static function render(string $template, array $data = []): void
    {
        $path = __DIR__ . '/../Views/' . $template . '.php';

        if (!file_exists($path)) {
            http_response_code(500);
            echo "View not found: $template";
            return;
        }

        extract($data);
        include $path;
    }
}
