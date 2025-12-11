<?php

/**
 * Executes all SQL files in the 'db' directory.
 *
 * This function scans the 'db' directory for `.sql` files and executes them
 * against the database using the credentials from the environment configuration.
 * It outputs the name of each file as it is executed.
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
