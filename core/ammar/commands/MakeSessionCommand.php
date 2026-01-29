<?php

class MakeSessionCommand extends Command
{
    public function __construct()
    {
        parent::__construct('make:session', 'Create a new session');
    }

    public function execute($args)
    {
        $key = $args['1'] ?? readline("1- What's key? ");
        $value = $args['2'] ?? readline("2- What's value? ");

        Session::make($key, $value);
        Terminal::success('Session Created: '.Terminal::color($key, 'cyan'));
    }
}

$registry->register(new MakeSessionCommand());
