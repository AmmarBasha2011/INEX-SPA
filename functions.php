<?php

/**
 * Renders a component.
 *
 * @param string $name The name of the component to render.
 * @param array  $data An array of data to pass to the component.
 *
 * @return string The rendered component, or an error message if the component is not found.
 */
function component($name, $data = [])
{
    $componentPath = "components/{$name}.php";
    if (file_exists($componentPath)) {
        extract($data);
        ob_start();
        include $componentPath;

        return ob_get_clean();
    }

    return "Component '{$name}' not found.";
}
