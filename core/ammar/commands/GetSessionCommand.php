<?php

class GetSessionCommand extends Command
{
    public function __construct()
    {
        parent::__construct('get:session', 'Retrieve a session entry');
    }

    public function execute($args)
    {
        $key = $args['1'] ?? readline("1- What's key? ");
        if (!$key) {
            Terminal::error('Session key is required!');

            return;
        }

        $value = Session::get($key);
        Terminal::info('Session Value for '.Terminal::color($key, 'cyan').': '.($value ?? Terminal::color('Not Found', 'red')));
    }
}

$registry->register(new GetSessionCommand());
