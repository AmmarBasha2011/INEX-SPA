<?php

class ClearRoutesCommand extends Command
{
    public function __construct()
    {
        parent::__construct('clear:routes', 'Clear all route files');
    }

    public function execute($args)
    {
        $files = glob(WEB_FOLDER.'/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        Terminal::success('Route files cleared!');
    }
}

$registry->register(new ClearRoutesCommand());
