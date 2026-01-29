<?php

class ServeCommand extends Command {
    public function __construct() {
        parent::__construct('serve', 'Serve the application');
    }

    public function execute($args) {
        $port = $args['1'] ?? 8000;
        Terminal::header("INEX SPA Development Server");
        Terminal::info("Serving on " . Terminal::color("http://localhost:$port", 'cyan'));
        Terminal::info("Press Ctrl+C to stop.");
        echo PHP_EOL;

        passthru("php -S localhost:$port -t web");
    }
}

$registry->register(new ServeCommand());
