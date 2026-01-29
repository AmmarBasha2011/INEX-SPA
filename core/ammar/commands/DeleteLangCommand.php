<?php

class DeleteLangCommand extends Command {
    public function __construct() {
        parent::__construct('delete:lang', 'Delete a language file');
    }

    public function execute($args) {
        $langName = $args['1'] ?? readline("1- What's Language Name? ");
        if (!$langName) {
            Terminal::error("Language name is required!");
            return;
        }

        $file = LANG_FOLDER . "/$langName.json";
        if (file_exists($file)) {
            if (unlink($file)) {
                Terminal::success("Deleted language file: " . Terminal::color($langName . ".json", 'cyan'));
            } else {
                Terminal::error("Failed to delete language file!");
            }
        } else {
            Terminal::warning("Language file not found!");
        }
    }
}

$registry->register(new DeleteLangCommand());
