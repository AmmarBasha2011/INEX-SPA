<?php

class ClearCacheCommand extends Command {
    public function __construct() {
        parent::__construct('clear:cache', 'Clear all cache files');
    }

    public function execute($args) {
        $files = glob(CACHE_FOLDER . "/*");
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
        Terminal::success("Cache cleared!");
    }
}

$registry->register(new ClearCacheCommand());
