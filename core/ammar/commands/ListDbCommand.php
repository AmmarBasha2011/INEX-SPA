<?php

class ListDbCommand extends Command
{
    public function __construct()
    {
        parent::__construct('list:db', 'List all database files');
    }

    public function execute($args)
    {
        $files = array_merge(
            glob(DB_FOLDER.'/*.sql'),
            glob(DB_FOLDER.'/**/*.sql')
        );

        Terminal::header('Available DB Files');

        if (empty($files)) {
            Terminal::warning('No DB files found in '.DB_FOLDER);

            return;
        }

        foreach ($files as $file) {
            $relativePath = str_replace(DB_FOLDER.'/', '', $file);
            echo '  '.Terminal::color('→', 'cyan').' '.$relativePath.PHP_EOL;
        }
        echo PHP_EOL;
    }
}

$registry->register(new ListDbCommand());
