<?php

class Router {
    protected \$routes = [];
    protected \$AhmedTemplate; // To store the AhmedTemplate instance

    public function __construct(array \$routes, \$AhmedTemplateInstance) {
        \$this->routes = \$routes;
        \$this->AhmedTemplate = \$AhmedTemplateInstance;
    }

    protected function loadAssets() {
        // Helper function to load Bootstrap and common JS scripts.
        // This logic is based on functions from core/functions/PHP/getPage.php
        if (function_exists('loadBootstrap')) {
            loadBootstrap();
        }
        if (function_exists('loadScripts')) {
            loadScripts();
        }
    }

    public function dispatch(\$uri, \$method) {
        // Remove query string from URI
        if (false !== \$pos = strpos(\$uri, '?')) {
            \$uri = substr(\$uri, 0, \$pos);
        }
        \$uri = rawurldecode(\$uri);

        foreach (\$this->routes as \$route) {
            // Check method
            if (strtoupper(\$route['method']) !== 'ANY' && strtoupper(\$route['method']) !== strtoupper(\$method)) {
                continue;
            }

            \$pattern = preg_replace('/\\{[a-zA-Z0-9_]+\\}/', '([a-zA-Z0-9_]+)', preg_quote(\$route['path'], '/'));
            \$pattern = '/^' . str_replace('\\/', '/', \$pattern) . '$/'; // Allow slashes in placeholders and ensure they are treated as literal slashes

            \$matches = [];
            if (preg_match(\$pattern, \$uri, \$matches)) {
                array_shift(\$matches); // Remove the full match

                \$params = [];
                if (!empty(\$route['dynamic_segment']) && !empty(\$matches)) {
                    // If dynamic_segment is a string, assume single parameter
                    if (is_string(\$route['dynamic_segment'])) {
                        \$params[\$route['dynamic_segment']] = \$matches[0];
                         // Make it available via $_GET for compatibility with existing templates
                        \$_GET[\$route['dynamic_segment']] = \$matches[0];
                    }
                    // If it's an array, map multiple matches
                    elseif (is_array(\$route['dynamic_segment'])) {
                        foreach(\$route['dynamic_segment'] as \$index => \$segment_name) {
                            if(isset(\$matches[\$index])) {
                                \$params[\$segment_name] = \$matches[\$index];
                                \$_GET[\$segment_name] = \$matches[\$index];
                            }
                        }
                    }
                }


                // Handle the route
                \$isApi = isset(\$route['is_api']) && \$route['is_api'] === true;

                if (\$route['type'] === 'preview') {
                    if (!\$isApi) {
                        \$this->loadAssets();
                    }
                    \$templatePath = 'web/' . \$route['handler'];
                    if (file_exists(\$templatePath)) {
                        // Use the passed AhmedTemplate instance to render
                        echo \$this->AhmedTemplate->render(\$templatePath, \$params);
                    } else {
                        // Log error: Template file not found
                        error_log("Router error: Template file not found at " . \$templatePath);
                        \$this->handleError(500, "Template file missing: " . \$route['handler']);
                    }
                    return; // Route handled
                } elseif (\$route['type'] === 'command') {
                    if (is_callable(\$route['handler'])) {
                        call_user_func_array(\$route['handler'], \$params ? array_values(\$params) : []);
                    } elseif (is_string(\$route['handler']) && function_exists(\$route['handler'])) {
                        call_user_func_array(\$route['handler'], \$params ? array_values(\$params) : []);
                    } else {
                         // Log error: Handler function not found or not callable
                        error_log("Router error: Handler function not found or not callable: " . \$route['handler']);
                        \$this->handleError(500, "Invalid command handler for route: " . \$route['path']);
                    }
                    return; // Route handled
                }
            }
        }

        // No route matched
        \$this->handleError(404);
    }

    protected function handleError(\$statusCode, \$message = '') {
        http_response_code(\$statusCode);
        \$errorPagePath = "core/errors/" . \$statusCode . ".php";
        if (file_exists(\$errorPagePath)) {
            // For 404, 403, etc., we might want to load assets if it's not an API call
            if (!isset(\$route['is_api']) || \$route['is_api'] === false) {
                 // Attempt to load assets for error pages, but ensure functions exist
                if (function_exists('loadScripts')) {
                    loadScripts(); // From getPage.php, ensure it's loaded or integrated
                }
            }
            include \$errorPagePath;
        } else {
            // Fallback simple error message
            echo "Error " . \$statusCode . ": " . \$message;
            if (empty(\$message) && \$statusCode == 404) {
                echo "Page not found.";
            }
        }
        exit;
    }
}

?>
