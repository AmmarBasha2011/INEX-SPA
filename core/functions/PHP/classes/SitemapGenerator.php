<?php

/**
 * A class for generating a sitemap.xml file.
 */
class SitemapGenerator
{
    /**
     * Generates the sitemap.xml file.
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
            if ($route === 'index') {
                $route = '';
            }
            $xml .= '<url><loc>'.getEnvValue('WEBSITE_URL').$route."</loc></url>\n";
        }

        $xml .= '</urlset>';
        file_put_contents(__DIR__.'/../../../../public/sitemap.xml', $xml);
    }

    /**
     * Recursively gets all routes from the web directory.
     *
     * @param string $dir      The directory to scan.
     * @param string $basePath The base path for the routes.
     *
     * @return array An array of routes.
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
                $routes = array_merge($routes, self::getRoutes($fullPath, $routePath.'/'));
            } else {
                if (preg_match('/^(.+)_dynamic\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/{id}';
                } elseif (preg_match('/^(.+)_request_(GET|POST|PUT|DELETE)\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                } elseif (preg_match('/^(.+)_api\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api';
                } elseif (preg_match('/^(.+)_dynamic_api\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api/{id}';
                } else {
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                }
            }
        }

        return array_unique($routes);
    }
}
