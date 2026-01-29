<?php

class MakeCacheCommand extends Command
{
    public function __construct()
    {
        parent::__construct('make:cache', 'Create a new cache entry');
    }

    public function execute($args)
    {
        $key = $args['1'] ?? readline('1- Enter cache key: ');
        $value = $args['2'] ?? readline('2- Enter cache value: ');
        $expiration = $args['3'] ?? readline('3- Enter expiration time (in seconds): ');

        if (Cache::set($key, $value, $expiration)) {
            Terminal::success('Cache entry created for key: '.Terminal::color($key, 'cyan'));
        } else {
            Terminal::error('Could not create cache entry!');
        }
    }
}

$registry->register(new MakeCacheCommand());
