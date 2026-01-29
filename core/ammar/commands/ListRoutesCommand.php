<?php

class ListRoutesCommand extends Command
{
    public function __construct()
    {
        parent::__construct('list:routes', 'List all route files');
    }

    public function execute($args)
    {
        $files = array_merge(
            glob(WEB_FOLDER.'/*.ahmed.php'),
            glob(WEB_FOLDER.'/**/*.ahmed.php')
        );

        Terminal::header('Available Routes');

        if (empty($files)) {
            Terminal::warning('No routes found in '.WEB_FOLDER);

            return;
        }

        foreach ($files as $file) {
            $relativePath = str_replace(WEB_FOLDER.'/', '', $file);
            echo '  '.Terminal::color('→', 'cyan').' '.$relativePath.PHP_EOL;
        }
        echo PHP_EOL;
    }
}

$registry->register(new ListRoutesCommand());
