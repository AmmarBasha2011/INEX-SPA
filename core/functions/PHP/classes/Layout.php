<?php

/**
 * Layout and Section Management.
 *
 * This file contains the Layout class, a static utility for creating and
 * managing template inheritance and content sections.
 */

/**
 * A class for managing layouts and content sections.
 *
 * This class provides a simple system for defining reusable layouts and injecting
 * content into them from different template files. It supports capturing content
 * for named sections using output buffering and rendering them within a master
 * layout file. All methods are static.
 */
class Layout
{
    /**
     * An associative array to store the captured content of named sections.
     * Keys are section names, values are the captured HTML content.
     *
     * @var array
     */
    private static $sections = [];

    /**
     * The name of the section that is currently being captured by output buffering.
     *
     * @var string|null
     */
    private static $currentSection = null;

    /**
     * Starts capturing content for a named section.
     *
     * Begins output buffering and sets the current section name. All output
     * after this call will be captured until `end()` is called.
     *
     * @param string $section The name of the section to start capturing.
     *
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
     * Stops capturing content for the current section and stores it.
     *
     * Ends the output buffering for the current section, retrieves the captured
     * content, and stores it in the `$sections` array.
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
     * specified layout file, making the provided data available to both.
     *
     * @param string $layoutName  The name of the layout file (without extension) in the `layouts/` directory.
     * @param string $contentFile The name of the content file (without extension) in the `web/` directory.
     * @param string $requestType The HTTP request type (e.g., 'GET', 'POST') used for locating request-specific files.
     * @param array  $data        An associative array of data to be extracted into variables for the layout and content files.
     *
     * @return void
     */
    public static function render($layoutName, $contentFile, $requestType = 'GET', $data = [])
    {
        global $Ahmed;

        $layoutPath = __DIR__."/../../../../layouts/$layoutName.ahmed.php";

        // Define potential paths for the content file to support various routing conventions.
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
        // The AhmedTemplate instance will render the layout, which in turn will call section()
        echo $Ahmed->render($layoutPath);
    }

    /**
     * Retrieves and outputs the content of a named section.
     *
     * This method is intended to be called from within a layout file to inject
     * the content that was captured in a content file.
     *
     * @param string $section The name of the section to retrieve.
     *
     * @return string The captured HTML content of the section, or an error message if the section was not found.
     */
    public static function section($section)
    {
        return self::$sections[$section] ?? "❌ Error: Section '$section' not found!";
    }
}
