<?php

class MakeLayoutCommand extends Command {
    public function __construct() {
        parent::__construct('make:layout', 'Create a new layout file');
    }

    public function execute($args) {
        $layoutName = $args['1'] ?? readline("1- What's Layout Name? ");
        if (!$layoutName) {
            Terminal::error("Layout name is required!");
            return;
        }

        $file = LAYOUT_FOLDER . "/$layoutName.ahmed.php";
        if (file_exists($file)) {
            Terminal::warning("Layout file already exists!");
        } else {
            if (file_put_contents($file, "{}\n")) {
                Terminal::success("Layout file created: " . Terminal::color($layoutName . ".ahmed.php", 'cyan'));
            } else {
                Terminal::error("Could not create layout file!");
            }
        }
    }
}

$registry->register(new MakeLayoutCommand());
