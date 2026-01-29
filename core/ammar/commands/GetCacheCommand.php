<?php

class GetCacheCommand extends Command {
    public function __construct() {
        parent::__construct('get:cache', 'Retrieve a cache entry');
    }

    public function execute($args) {
        $key = $args['1'] ?? readline("1- Enter cache key: ");
        if (!$key) {
            Terminal::error("Cache key is required!");
            return;
        }

        $value = Cache::get($key);
        if ($value === false) {
            Terminal::warning("Cache entry not found for key: " . Terminal::color($key, 'cyan'));
        } else {
            Terminal::info("Cache value for " . Terminal::color($key, 'cyan') . ": " . $value);
        }
    }
}

$registry->register(new GetCacheCommand());
