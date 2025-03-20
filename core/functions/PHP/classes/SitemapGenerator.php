<?php

class SitemapGenerator {
    public static function generate() {
        $routesDir = realpath(__DIR__ . "/../../../../web/"); // Set correct path
        if (!$routesDir) {
            die("Error: Routes directory not found.");
        }

        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

        foreach (self::getRoutes($routesDir) as $route) {
            if ($route === 'index') {
                $route = '';
            }
            $xml .= "<url><loc>" . getEnvValue('WEBSITE_URL') . $route . "</loc></url>\n";
        }

        $xml .= "</urlset>";
        file_put_contents(__DIR__ . "/../../../../public/sitemap.xml", $xml); // Save to public
    }

    private static function getRoutes($dir, $basePath = '') {
        $files = scandir($dir);
        $routes = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;

            $fullPath = $dir . '/' . $file;
            $routePath = $basePath . $file;

            if (is_dir($fullPath)) {
                // Recursively scan subfolders
                $routes = array_merge($routes, self::getRoutes($fullPath, $routePath . '/'));
            } else {
                // Extract the route name
                if (preg_match('/^(.+)_dynamic\.php$/', $file, $matches)) {
                    // Dynamic route: [route]_dynamic.php -> /route/{id}
                    $routes[] = str_replace('.php', '', $routePath) . "/{id}";
                } elseif (preg_match('/^(.+)_request_(GET|POST|PUT|DELETE)\.php$/', $file, $matches)) {
                    // Request type route: [route]_request_[requestType].php -> /route
                    $routes[] = str_replace('.php', '', $routePath);
                } else {
                    // Normal route: [route].php
                    $routes[] = str_replace('.php', '', $routePath);
                }
            }
        }

        return array_unique($routes); // Remove duplicates
    }
}
