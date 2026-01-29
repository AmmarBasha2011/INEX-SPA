<?php

class ClearStartCommand extends Command
{
    public function __construct()
    {
        parent::__construct('clear:start', 'Clear all startup files');
    }

    public function execute($args)
    {
        $fileslist = [
            WEB_FOLDER.'/index.ahmed.php',
            PUBLIC_FOLDER.'/script.js',
            PUBLIC_FOLDER.'/style.css',
            PUBLIC_FOLDER.'/sitemap.xml',
            LAYOUT_FOLDER.'/main.ahmed.php',
            LANG_FOLDER.'/ar.json',
            LANG_FOLDER.'/en.json',
            DB_FOLDER.'/createusersTable_2025_03_16_08_47_56.sql',
            CACHE_FOLDER.'/14c4b06b824ec593239362517f538b29.cache',
        ];

        foreach ($fileslist as $file) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    Terminal::success('Deleted: '.$file);
                } else {
                    Terminal::error('Failed to delete: '.$file);
                }
            } else {
                Terminal::info('Not found: '.$file);
            }
        }

        Terminal::success('All startup files have been processed!');
    }
}

$registry->register(new ClearStartCommand());
