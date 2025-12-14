<?php

/**
 * Automatically generates a `sitemap.xml` file for the application.
 *
 * This utility class scans the `web` directory to discover all public routes based
 * on the filenames of the `.ahmed.php` templates. It then compiles these routes
 * into a `sitemap.xml` file and saves it in the `public` directory, making it
 * accessible to search engine crawlers.
 */
class SitemapGenerator
{
    /**
     * The main method to generate and save the `sitemap.xml` file.
     *
     * This method orchestrates the sitemap generation process by:
     * 1. Locating the application's routes directory.
     * 2. Calling a helper method to recursively find all routes.
     * 3. Building the XML structure compliant with the sitemap protocol.
     * 4. Writing the final XML content to `public/sitemap.xml`.
     *
     * @return void
     */
    public static function generate()
    {
        $routesDir = realpath(__DIR__.'/../../../../web/'); // Set correct path
        if (!$routesDir) {
            exit('Error: Routes directory not found.');
        }

        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

        foreach (self::getRoutes($routesDir) as $route) {
            if ($route === 'index') {
                $route = '';
            }
            $xml .= '<url><loc>'.getEnvValue('WEBSITE_URL').$route."</loc></url>\n";
        }

        $xml .= '</urlset>';
        file_put_contents(__DIR__.'/../../../../public/sitemap.xml', $xml); // Save to public
    }

    /**
     * Recursively scans the routes directory to discover all unique URL paths.
     *
     * This private helper method navigates the file structure of the `web` directory.
     * It interprets specific filename conventions (e.g., `_dynamic.ahmed.php`,
     * `_request_GET.ahmed.php`) to correctly format the route URLs for the sitemap.
     *
     * @param string $dir      The absolute path of the directory to scan.
     * @param string $basePath The current path relative to the `web` root, used for
     *                         building nested route URLs.
     *
     * @return array An array of unique, formatted route strings.
     */
    private static function getRoutes($dir, $basePath = '')
    {
        $files = scandir($dir);
        $routes = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $dir.'/'.$file;
            $routePath = $basePath.$file;

            if (is_dir($fullPath)) {
                // Recursively scan subfolders
                $routes = array_merge($routes, self::getRoutes($fullPath, $routePath.'/'));
            } else {
                // Extract the route name
                if (preg_match('/^(.+)_dynamic\.ahmed\.php$/', $file, $matches)) {
                    // Dynamic route: [route]_dynamic.ahmed.php -> /route/{id}
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/{id}';
                } elseif (preg_match('/^(.+)_request_(GET|POST|PUT|DELETE)\.ahmed\.php$/', $file, $matches)) {
                    // Request type route: [route]_request_[requestType].ahmed.php -> /route
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                } elseif (preg_match('/^(.+)_api\.ahmed\.php$/', $file, $matches)) {
                    // API route: [route]_api.ahmed.php -> /route/api
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api';
                } elseif (preg_match('/^(.+)_dynamic_api\.ahmed\.php$/', $file, $matches)) {
                    // Dynamic API route: [route]_dynamic_api.ahmed.php -> /route/api/{id}
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api/{id}';
                } else {
                    // Normal route: [route].ahmed.php
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                }
            }
        }

        return array_unique($routes); // Remove duplicates
    }
}
