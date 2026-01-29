<?php

class DeleteSessionCommand extends Command {
    public function __construct() {
        parent::__construct('delete:session', 'Delete a session');
    }

    public function execute($args) {
        $key = $args['1'] ?? readline("1- What's key? ");
        if (!$key) {
            Terminal::error("Session key is required!");
            return;
        }

        Session::delete($key);
        Terminal::success("Session Deleted: " . Terminal::color($key, 'cyan'));
    }
}

$registry->register(new DeleteSessionCommand());
