<?php
/**
 * Sitemap Generation Utility
 *
 * This file contains the SitemapGenerator class, a static utility for
 * automatically creating a `sitemap.xml` file by scanning the application's
 * route files.
 */

/**
 * A class to automatically generate a sitemap.xml file.
 *
 * This class scans the 'web' directory for template files (`.ahmed.php`) to
 * build a list of application routes. It then generates a compliant `sitemap.xml`
 * file and saves it in the 'public' directory, making it accessible to search engines.
 *
 * @package INEX\Core
 */
class SitemapGenerator
{
    /**
     * Generates and saves the sitemap.xml file.
     *
     * This method orchestrates the sitemap generation process. It finds all
     * valid routes by scanning the `web` directory, formats them into XML,
     * and writes the final `sitemap.xml` file to the public directory.
     *
     * @return void
     */
    public static function generate()
    {
        $routesDir = realpath(__DIR__.'/../../../../web/');
        if (!$routesDir) {
            exit('Error: Routes directory not found.');
        }

        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

        foreach (self::getRoutes($routesDir) as $route) {
            // Special case for the homepage, which should be the root URL.
            if ($route === 'index') {
                $route = '';
            }
            $xml .= '<url><loc>'.getEnvValue('WEBSITE_URL').$route."</loc></url>\n";
        }

        $xml .= '</urlset>';
        file_put_contents(__DIR__.'/../../../../public/sitemap.xml', $xml);
    }

    /**
     * Recursively scans a directory to find and parse all application routes.
     *
     * This method interprets different file naming conventions to identify standard,
     * dynamic, API, and request-specific routes from the `.ahmed.php` files.
     *
     * @param string $dir      The absolute path to the directory to scan.
     * @param string $basePath The base path used for building the route URL during recursion.
     *
     * @return array An array of unique route strings.
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
                // Recursively scan subdirectories.
                $routes = array_merge($routes, self::getRoutes($fullPath, $routePath.'/'));
            } else {
                // Parse the filename to determine the route.
                if (preg_match('/^(.+)_dynamic\.ahmed\.php$/', $file, $matches)) {
                    // Dynamic route: e.g., 'post_dynamic.ahmed.php' -> 'post/{id}'
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/{id}';
                } elseif (preg_match('/^(.+)_request_(GET|POST|PUT|DELETE)\.ahmed\.php$/', $file, $matches)) {
                    // Request-specific route: e.g., 'contact_request_POST.ahmed.php' -> 'contact_request_POST'
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                } elseif (preg_match('/^(.+)_api\.ahmed\.php$/', $file, $matches)) {
                    // API route: e.g., 'users_api.ahmed.php' -> 'users_api/api'
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api';
                } elseif (preg_match('/^(.+)_dynamic_api\.ahmed\.php$/', $file, $matches)) {
                    // Dynamic API route: e.g., 'user_dynamic_api.ahmed.php' -> 'user_dynamic_api/api/{id}'
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api/{id}';
                } else {
                    // Standard route: e.g., 'about.ahmed.php' -> 'about'
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                }
            }
        }

        return array_unique($routes); // Ensure no duplicate routes are returned.
    }
}
