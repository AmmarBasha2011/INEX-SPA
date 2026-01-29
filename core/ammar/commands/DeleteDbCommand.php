<?php

class DeleteDbCommand extends Command {
    public function __construct() {
        parent::__construct('delete:db', 'Delete an existing DB File');
    }

    public function execute($args) {
        $action = $args['1'] ?? readline("1- What's the DB file for (create, delete, addFieldTo): ");
        $table = $args['2'] ?? readline("2- What's table name? ");
        if (!$action || !$table) {
            Terminal::error("Action and table name are required!");
            return;
        }

        $files = glob(DB_FOLDER . "/{$action}{$table}Table_*.sql");
        if (empty($files)) {
            Terminal::warning("No matching DB files found!");
        } else {
            foreach ($files as $file) {
                if (unlink($file)) {
                    Terminal::success("Deleted: " . Terminal::color(basename($file), 'cyan'));
                } else {
                    Terminal::error("Failed to delete: " . basename($file));
                }
            }
        }
    }
}

$registry->register(new DeleteDbCommand());
