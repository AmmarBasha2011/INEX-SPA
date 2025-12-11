<?php

/**
 * A class for managing layouts and content sections.
 *
 * This class provides a simple system for defining reusable layouts and injecting
 * content into them from different template files. It supports capturing content
 * for named sections and rendering them within a master layout file.
 */
class Layout
{
    /**
     * An associative array to store the content of captured sections.
     * @var array
     */
    private static $sections = [];

    /**
     * The name of the section currently being captured.
     * @var string|null
     */
    private static $currentSection = null;

    /**
     * Starts capturing content for a named section.
     *
     * @param string $section The name of the section to start.
     * @return void
     */
    public static function start($section)
    {
        if (self::$currentSection !== null) {
            exit('❌ Error: Nested sections are not allowed!');
        }
        self::$currentSection = $section;
        ob_start();
    }

    /**
     * Stops capturing content for the current section.
     *
     * @return void
     */
    public static function end()
    {
        if (self::$currentSection === null) {
            exit('❌ Error: No section has been started!');
        }
        self::$sections[self::$currentSection] = ob_get_clean();
        self::$currentSection = null;
    }

    /**
     * Renders a layout with a specific content file.
     *
     * This method searches for the content file in various possible locations
     * (standard, dynamic, request-specific) and then renders it within the
     * specified layout file.
     *
     * @param string $layoutName The name of the layout file (without extension).
     * @param string $contentFile The name of the content file to be included.
     * @param string $requestType The HTTP request type (e.g., 'GET', 'POST').
     * @param array $data Data to be extracted into variables for the layout and content.
     * @return void
     */
    public static function render($layoutName, $contentFile, $requestType = 'GET', $data = [])
    {
        global $Ahmed;

        $layoutPath = __DIR__."/../../../../layouts/$layoutName.ahmed.php";

        // Support for dynamic paths and request types
        $contentPaths = [
            __DIR__."/../../../../web/$contentFile.ahmed.php", // Standard path
            __DIR__."/../../../../web/{$contentFile}_dynamic.ahmed.php", // Dynamic path
            __DIR__."/../../../../web/{$contentFile}_request_$requestType.ahmed.php", // Path with request type
            __DIR__."/../../../../web/{$contentFile}_dynamic_api.ahmed.php", // Dynamic API path
            __DIR__."/../../../../web/{$contentFile}_request_{$requestType}_api.ahmed.php", // API path with request type
        ];

        $foundContentPath = null;
        foreach ($contentPaths as $path) {
            if (file_exists($path)) {
                $foundContentPath = $path;
                break;
            }
        }

        if (!file_exists($layoutPath)) {
            exit("❌ Error: Layout file '$layoutName.php' not found at '$layoutPath'!");
        }
        if (!$foundContentPath) {
            exit("❌ Error: Content file for '$contentFile' not found in expected paths!");
        }

        extract($data);
        echo $Ahmed->render($layoutPath);
    }

    /**
     * Retrieves the content of a named section.
     *
     * @param string $section The name of the section.
     * @return string The content of the section, or an error message if not found.
     */
    public static function section($section)
    {
        return self::$sections[$section] ?? "❌ Error: Section '$section' not found!";
    }
}
