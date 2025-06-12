<?php

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
