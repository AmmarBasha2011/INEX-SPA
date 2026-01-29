<?php

class UpdateCacheCommand extends Command {
    public function __construct() {
        parent::__construct('update:cache', 'Update an existing cache entry');
    }

    public function execute($args) {
        $key = $args['1'] ?? readline("1- Enter cache key: ");
        $newValue = $args['2'] ?? readline("2- Enter new cache value: ");

        if (!$key) {
            Terminal::error("Cache key is required!");
            return;
        }

        if (Cache::update($key, $newValue)) {
            Terminal::success("Cache updated for key: " . Terminal::color($key, 'cyan'));
        } else {
            Terminal::error("Cache key not found: " . Terminal::color($key, 'red'));
        }
    }
}

$registry->register(new UpdateCacheCommand());
