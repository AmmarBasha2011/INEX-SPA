<?php

class MakeAuthCommand extends Command {
    public function __construct() {
        parent::__construct('make:auth', 'Create an auth db file');
    }

    public function execute($args) {
        $action = "create";
        $table = "users";
        $timestamp = date('Y_m_d_H_i_s');
        $filename = "{$action}{$table}Table_{$timestamp}.sql";
        $filePath = DB_FOLDER . '/' . $filename;
        $sqlTemplate = UserAuth::generateSQL();

        if (file_put_contents($filePath, $sqlTemplate)) {
            Terminal::success("Auth DB file created: " . Terminal::color($filename, 'cyan'));
        } else {
            Terminal::error("Could not create Auth DB file!");
        }
    }
}

$registry->register(new MakeAuthCommand());
