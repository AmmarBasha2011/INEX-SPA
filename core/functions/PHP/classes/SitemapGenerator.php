<?php

/**
 * Automatically generates a `sitemap.xml` file for the application.
 *
 * This utility class scans the `/web` directory to discover all public routes based
 * on the filenames of the `.ahmed.php` templates. It then compiles these routes
 * into a standard `sitemap.xml` file and saves it in the `/public` directory,
 * making it accessible to search engine crawlers to improve SEO.
 */
class SitemapGenerator
{
    /**
     * The main method to generate and save the `sitemap.xml` file.
     *
     * This method orchestrates the entire sitemap generation process. It:
     * 1. Locates the application's routes directory (`/web`).
     * 2. Calls a helper method (`getRoutes`) to recursively find all routes.
     * 3. Builds the XML content in a string, compliant with the sitemap protocol v0.9.
     * 4. Writes the final XML content to `public/sitemap.xml`, overwriting any
     *    existing file.
     *
     * @return void This method outputs status messages and writes to a file but does not return a value.
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
            // The 'index' route should correspond to the root URL.
            if ($route === 'index') {
                $route = '';
            }
            $xml .= '<url><loc>'.getEnvValue('WEBSITE_URL').$route."</loc></url>\n";
        }

        $xml .= '</urlset>';
        file_put_contents(__DIR__.'/../../../../public/sitemap.xml', $xml);
    }

    /**
     * Recursively scans the routes directory to discover all unique URL paths.
     *
     * This private helper method navigates the file structure of the `/web` directory.
     * It interprets specific filename conventions to correctly format the route URLs
     * for the sitemap. For example:
     * - `about.ahmed.php` becomes `/about`
     * - `post_dynamic.ahmed.php` becomes `/post/{id}`
     * - `contact_request_GET.ahmed.php` becomes `/contact_request_GET`
     *
     * @param string $dir      The absolute path of the directory to scan.
     * @param string $basePath (Internal) The current path relative to the `/web` root, used for
     *                         building nested route URLs during recursion.
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
                // Recursively scan subfolders.
                $routes = array_merge($routes, self::getRoutes($fullPath, $routePath.'/'));
            } else {
                // Extract the route name based on file naming conventions.
                if (preg_match('/^(.+)_dynamic\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/{id}';
                } elseif (preg_match('/^(.+)_request_(GET|POST|PUT|DELETE)\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                } elseif (preg_match('/^(.+)_api\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api';
                } elseif (preg_match('/^(.+)_dynamic_api\.ahmed\.php$/', $file, $matches)) {
                    $routes[] = str_replace('.ahmed.php', '', $routePath).'/api/{id}';
                } else {
                    // Standard route.
                    $routes[] = str_replace('.ahmed.php', '', $routePath);
                }
            }
        }

        return array_unique($routes); // Ensure no duplicate routes are returned.
    }
}
