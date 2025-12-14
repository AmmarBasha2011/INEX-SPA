<?php

/**
 * Executes all SQL migration files located in the `db` directory.
 *
 * This function serves as a simple database migration runner. It scans the `db`
 * directory for all files with a `.sql` extension and executes each one sequentially.
 * It retrieves the necessary database credentials from the environment configuration
 * and outputs the name of each file as it is being processed.
 *
 * @return void
 */
function runDB()
{
    $files = glob(__DIR__.'/../../../db/*.sql');
    foreach ($files as $file) {
        echo 'Running: '.basename($file)."\n";
        executeSQLFilePDO(
            getEnvValue('DB_HOST'),
            getEnvValue('DB_USER'),
            getEnvValue('DB_PASS'),
            getEnvValue('DB_NAME'),
            $file
        );
    }
}
