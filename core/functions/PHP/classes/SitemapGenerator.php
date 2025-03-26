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
                if (preg_match('/^(.+)_dynamic\.ahmed\.php$/', $file, $matches)) {
                    // Dynamic route: [route]_dynamic.ahmed.php -> /route/{id}
                    $routes[] = str_replace('.ahmed.php', '', $routePath) . "/{id}";
                } elseif (preg_match('/^(.+)_request_(GET|POST|PUT|DELETE)\.ahmed\.php$/', $file, $matches)) {
                    // Request type route: [route]_request_[requestType].ahmed.php -> /route
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                } elseif (preg_match('/^(.+)_api\.ahmed\.php$/', $file, $matches)) {
                    // API route: [route]_api.ahmed.php -> /route/api
                    $routes[] = str_replace('.ahmed.php', '', $routePath) . "/api";
                } elseif (preg_match('/^(.+)_dynamic_api\.ahmed\.php$/', $file, $matches)) {
                    // Dynamic API route: [route]_dynamic_api.ahmed.php -> /route/api/{id}
                    $routes[] = str_replace('.ahmed.php', '', $routePath) . "/api/{id}";
                } else {
                    // Normal route: [route].ahmed.php
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                }
            }
        }

        return array_unique($routes); // Remove duplicates
    }
}