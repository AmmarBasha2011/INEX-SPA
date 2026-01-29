<?php

class ListImportCommand extends Command {
    public function __construct() {
        parent::__construct('list:import', 'List all libraries');
    }

    public function execute($args) {
        $importDir = PROJECT_ROOT . '/core/import';

        Terminal::header("Available Imports");

        if (!is_dir($importDir)) {
            Terminal::warning("No imports found!");
            return;
        }

        $imports = array_diff(scandir($importDir), ['.', '..']);
        $found = false;
        foreach ($imports as $import) {
            if ($import === "package.json") continue;
            echo "  " . Terminal::color("→", 'cyan') . " " . $import . PHP_EOL;
            $found = true;
        }

        if (!$found) {
            Terminal::warning("No imports found!");
        }
        echo PHP_EOL;
    }
}

$registry->register(new ListImportCommand());
