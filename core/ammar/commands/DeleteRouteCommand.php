<?php

class DeleteRouteCommand extends Command {
    public function __construct() {
        parent::__construct('delete:route', 'Delete an existing Route File');
    }

    public function execute($args) {
        $route = $args['1'] ?? readline("1- What's route name? ");
        if (!$route) {
            Terminal::error("Route name is required!");
            return;
        }

        $patterns = [
            "$route.ahmed.php",
            "{$route}_dynamic.ahmed.php",
            "{$route}_request_GET.ahmed.php",
            "{$route}_request_POST.ahmed.php",
            "{$route}_request_PUT.ahmed.php",
            "{$route}_request_PATCH.ahmed.php",
            "{$route}_request_DELETE.ahmed.php",
            "{$route}_request_GET_api.ahmed.php",
            "{$route}_request_POST_api.ahmed.php",
            "{$route}_request_PUT_api.ahmed.php",
            "{$route}_request_PATCH_api.ahmed.php",
            "{$route}_request_DELETE_api.ahmed.php",
            "{$route}_dynamic_api.ahmed.php"
        ];

        $deleted = false;
        foreach ($patterns as $pattern) {
            $filePath = WEB_FOLDER . '/' . $pattern;
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    Terminal::success("Deleted: " . Terminal::color($pattern, 'cyan'));
                    $deleted = true;
                } else {
                    Terminal::error("Failed to delete: " . $pattern);
                }
            }
        }

        if (!$deleted) {
            Terminal::warning("No matching route files found!");
        }
    }
}

$registry->register(new DeleteRouteCommand());
