<?php

function component($name, $data = [])
{
    $componentPath = "components/{$name}.php";
    if (file_exists($componentPath)) {
        if (getEnvValue('DEV_MODE') == 'true') {
            ComponentMemoryProfiler::startComponent($name);
        }

        // Extract the data array into variables for the component file
        extract($data);

        // Start output buffering
        ob_start();

        // Include the component file
        include $componentPath;

        // Get the contents of the buffer and clean it
        $output = ob_get_clean();

        if (getEnvValue('DEV_MODE') == 'true') {
            ComponentMemoryProfiler::endComponent($name);
        }

        return $output;
    }

    // Return an error message if the component file is not found
    return "Component '{$name}' not found.";
}
