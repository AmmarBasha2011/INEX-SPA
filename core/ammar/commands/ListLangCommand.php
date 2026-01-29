<?php

class ListLangCommand extends Command
{
    public function __construct()
    {
        parent::__construct('list:lang', 'List all languages');
    }

    public function execute($args)
    {
        $files = glob(LANG_FOLDER.'/*.json');

        Terminal::header('Available Languages');

        if (!$files) {
            Terminal::warning('No language files found!');

            return;
        }

        foreach ($files as $file) {
            echo '  '.Terminal::color('→', 'cyan').' '.basename($file, '.json').PHP_EOL;
        }
        echo PHP_EOL;
    }
}

$registry->register(new ListLangCommand());
