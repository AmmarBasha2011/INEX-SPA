<?php

function component($name, $data = [])
{
    $componentPath = "components/{$name}.php";
    if (file_exists($componentPath)) {
        // Extract the data array into variables for the component file
        extract($data);

        // Start output buffering
        ob_start();

        // Include the component file
        include $componentPath;

        // Get the contents of the buffer and clean it
        return ob_get_clean();
    }

    // Return an error message if the component file is not found
    return "Component '{$name}' not found.";
}
