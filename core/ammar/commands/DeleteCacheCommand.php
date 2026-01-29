<?php

class DeleteCacheCommand extends Command {
    public function __construct() {
        parent::__construct('delete:cache', 'Delete a cache entry');
    }

    public function execute($args) {
        $key = $args['1'] ?? readline("1- Enter cache key: ");
        if (!$key) {
            Terminal::error("Cache key is required!");
            return;
        }

        Cache::delete($key);
        Terminal::success("Cache deleted for key: " . Terminal::color($key, 'cyan'));
    }
}

$registry->register(new DeleteCacheCommand());
