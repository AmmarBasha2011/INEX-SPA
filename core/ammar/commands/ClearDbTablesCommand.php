<?php

require_once PROJECT_ROOT.'/core/functions/PHP/classes/ClearDBTables.php';

class ClearDbTablesCommand extends Command
{
    public function __construct()
    {
        parent::__construct('clear:db:tables', 'Clear all DB tables');
    }

    public function execute($args)
    {
        Terminal::warning('This will delete ALL tables in the database!');
        $confirmation = strtolower(readline('Are you sure? (yes/no): '));
        if ($confirmation !== 'yes') {
            Terminal::info('Operation cancelled.');

            return;
        }

        ClearDBTables::run();
    }
}

$registry->register(new ClearDbTablesCommand());
