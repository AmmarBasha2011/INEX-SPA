<?php

class MakeRouteCommand extends Command
{
    public function __construct()
    {
        parent::__construct('make:route', 'Create a new Route File');
    }

    public function execute($args)
    {
        $route = $args['1'] ?? readline("1- What's route name? ");
        if (!$route) {
            Terminal::error('Route name is required!');

            return;
        }

        $filedata = "<?php\n// Route handler for $route\n";
        if ($route == 'index') {
            $filedata .= "// This is the default route handler.\n";
            $filename = 'index.ahmed.php'; // Original script had a bug here, $filename was not defined before usage.
            $filePath = WEB_FOLDER.'/'.$filename;
            file_put_contents($filePath, $filedata);
            Terminal::success('Route file created: '.Terminal::color($filename, 'cyan'));

            return;
        } else {
            $filedata .= "// This route handles requests for $route.\n";
        }

        $isDynamic = strtolower($args['2'] ?? readline('2- Is this route dynamic? (yes/no): ')) === 'yes';

        $method = 'GET';
        if ($isDynamic) {
            $filename = "{$route}_dynamic";
        } else {
            $method = strtoupper($args['3'] ?? readline("3- What's available Type of request (GET, POST, PUT, PATCH, DELETE): "));
            if (!in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
                Terminal::error('Invalid request type!');

                return;
            }
            if ($method == 'POST' || $method == 'PUT' || $method == 'PATCH' || $method == 'DELETE') {
                $filedata .= "validateCsrfToken();\n";
            }
            $filename = "{$route}_request_{$method}";
        }

        $isAPI = strtolower($args['4'] ?? readline('4- Is this API? (yes/no): ')) === 'yes';

        if ($isAPI) {
            $filename .= '_api.ahmed.php';
            if ($method != 'POST') {
                $filedata .= "validateCsrfToken();\n";
            }
        } else {
            $filename .= '.ahmed.php';
        }

        $filePath = WEB_FOLDER.'/'.$filename;

        // Check if there are slashes in filename (subdirectories)
        if (strpos($filename, '/') !== false) {
            $parentDirectory = dirname($filePath);
            if (!is_dir($parentDirectory)) {
                mkdir($parentDirectory, 0755, true);
            }
        }

        if (file_put_contents($filePath, $filedata)) {
            Terminal::success('Route file created: '.Terminal::color($filename, 'cyan'));
        } else {
            Terminal::error('Could not create route file!');
        }
    }
}

$registry->register(new MakeRouteCommand());
