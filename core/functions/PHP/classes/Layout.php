<?php

/**
 * Manages view layouts and content sections for building reusable template structures.
 *
 * This class provides a simple yet effective system for defining a master layout
 * and injecting content into it from various view files. It allows for capturing
 * blocks of content in named "sections" which can then be rendered in the layout.
 */
class Layout
{
    /**
     * Stores the captured content for each named section.
     * The keys are section names, and the values are their buffered content.
     *
     * @var array
     */
    private static $sections = [];

    /**
     * Tracks the name of the section that is currently being captured.
     * This is `null` when no section is active.
     *
     * @var string|null
     */
    private static $currentSection = null;

    /**
     * Begins capturing output for a named content section.
     *
     * All output generated after this call (until `end()` is called) will be
     * stored in a buffer associated with the given section name.
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
     * Stops capturing output for the current section and stores its content.
     *
     * This method retrieves the contents of the output buffer, assigns it to the
     * current section, and then clears the current section state.
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
     * Renders a final view by embedding a content file within a layout file.
     *
     * This method orchestrates the assembly of the final page. It dynamically
     * locates the correct content file based on routing conventions (standard,
     * dynamic, API, etc.) and then renders the main layout, which in turn will
     * display the captured sections.
     *
     * @param string $layoutName  The name of the master layout file (without the .ahmed.php extension)
     *                            located in the `/layouts` directory.
     * @param string $contentFile The base name of the content file from the `/web` directory.
     * @param string $requestType The current HTTP request type (e.g., 'GET', 'POST'), used
     *                            to locate request-specific content files.
     * @param array  $data        An associative array of data to be extracted into variables,
     *                            making them available to both the layout and content files.
     *
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
     * Retrieves and returns the captured content for a named section.
     *
     * This method is typically called within a layout file to inject the content
     * from a view file at a specific location.
     *
     * @param string $section The name of the section whose content is to be retrieved.
     *
     * @return string The captured HTML content of the section, or an error message if
     *                the section was not found.
     */
    public static function section($section)
    {
        return self::$sections[$section] ?? "❌ Error: Section '$section' not found!";
    }
}
