<?php

class RunDbCommand extends Command
{
    public function __construct()
    {
        parent::__construct('run:db', 'Run all .sql files in db folder');
    }

    public function execute($args)
    {
        $files = array_merge(
            glob(DB_FOLDER.'/*.sql'),
            glob(DB_FOLDER.'/**/*.sql')
        );

        if (empty($files)) {
            Terminal::warning('No DB files found to run.');

            return;
        }

        foreach ($files as $file) {
            $relativePath = str_replace(DB_FOLDER.'/', '', $file);
            Terminal::info('Running: '.Terminal::color($relativePath, 'cyan'));
            executeSQLFilePDO(
                getEnvValue('DB_HOST'),
                getEnvValue('DB_USER'),
                getEnvValue('DB_PASS'),
                getEnvValue('DB_NAME'),
                $file
            );
        }
        Terminal::success('All DB files executed!');
    }
}

$registry->register(new RunDbCommand());
