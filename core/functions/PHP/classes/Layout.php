<?php

/**
 * Manages view layouts and content sections for building reusable template structures.
 *
 * This class provides a simple yet effective system for defining a master layout
 * and injecting content into it from various view files. It allows developers to
 * define a consistent structure (like headers and footers) in one place and then
 * fill in the dynamic parts from other, more specific view files. This promotes
 * _ D_on't _R_epeat _Y_ourself (DRY) principles.
 */
class Layout
{
    /**
     * Stores the captured content for each named section.
     * The keys are the section names, and the values are their buffered HTML content.
     *
     * @var array
     */
    private static $sections = [];

    /**
     * Tracks the name of the section that is currently being captured via output buffering.
     * This is `null` when no section capturing is active.
     *
     * @var string|null
     */
    private static $currentSection = null;

    /**
     * Begins capturing output for a named content section.
     *
     * All HTML and other output generated after this call (until `end()` is called) will be
     * intercepted by an output buffer and stored in a variable associated with the given
     * section name. It is critical that every `start()` call is paired with an `end()` call.
     *
     * @param string $section The name of the section to start capturing (e.g., 'content', 'sidebar').
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
     * This method retrieves the contents of the active output buffer, assigns it to the
     * current section name in the `$sections` array, and then clears the current section
     * state to indicate that capturing has finished.
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
     * This method orchestrates the assembly of the final page. It dynamically locates the
     * correct content file based on routing conventions (e.g., standard, dynamic, API-specific)
     * and then renders the specified master layout. The layout file is expected to call the
     * `section()` method to inject the captured content blocks.
     *
     * @param string $layoutName  The name of the master layout file (without the .ahmed.php extension)
     *                            located in the `/layouts` directory.
     * @param string $contentFile The base name of the content file from the `/web` directory.
     * @param string $requestType The current HTTP request type (e.g., 'GET', 'POST'), used
     *                            to locate request-specific content files.
     * @param array  $data        An associative array of data to be extracted into variables,
     *                            making them available within the scope of both the layout and
     *                            the content files.
     *
     * @return void This method outputs the final rendered HTML directly.
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
     * This method is designed to be called from within a layout file to inject the content
     * that was captured in a view file using `start()` and `end()`. For example, a layout
     * might call `<?= Layout::section('content') ?>` to insert the main body of the page.
     *
     * @param string $section The name of the section whose content is to be retrieved.
     *
     * @return string The captured HTML content of the section, or an error message string if
     *                the section was defined and captured.
     */
    public static function section($section)
    {
        return self::$sections[$section] ?? "❌ Error: Section '$section' not found!";
    }
}
