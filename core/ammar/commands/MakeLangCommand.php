<?php

class MakeLangCommand extends Command {
    public function __construct() {
        parent::__construct('make:lang', 'Create a new language file');
    }

    public function execute($args) {
        $langName = $args['1'] ?? readline("1- What's Language Name? ");
        if (!$langName) {
            Terminal::error("Language name is required!");
            return;
        }

        $file = LANG_FOLDER . "/$langName.json";
        if (file_exists($file)) {
            Terminal::warning("Language file already exists!");
        } else {
            if (file_put_contents($file, "{}\n")) {
                Terminal::success("Language file created: " . Terminal::color($langName . ".json", 'cyan'));
            } else {
                Terminal::error("Could not create language file!");
            }
        }
    }
}

$registry->register(new MakeLangCommand());
