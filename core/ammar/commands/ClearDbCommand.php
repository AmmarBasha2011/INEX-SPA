<?php

class ClearDbCommand extends Command
{
    public function __construct()
    {
        parent::__construct('clear:db', 'Clear all DB files');
    }

    public function execute($args)
    {
        $files = glob(DB_FOLDER.'/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        Terminal::success('DB files cleared!');
    }
}

$registry->register(new ClearDbCommand());
