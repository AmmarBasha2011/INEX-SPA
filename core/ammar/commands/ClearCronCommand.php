<?php

class ClearCronCommand extends Command
{
    public function __construct()
    {
        parent::__construct('clear:cron', 'Delete ALL cron task files');
    }

    public function execute($args)
    {
        if (!is_dir(CRON_TASKS_DIR)) {
            Terminal::warning('Cron tasks directory not found.');

            return;
        }

        $taskFiles = glob(CRON_TASKS_DIR.'*.php');

        if (empty($taskFiles)) {
            Terminal::info('No cron tasks found to clear.');

            return;
        }

        Terminal::warning("This will delete ALL cron task files in 'core/cron/tasks/'.");

        $confirmation1 = strtolower(readline('Are you sure you want to proceed? (yes/no): '));
        if ($confirmation1 !== 'yes') {
            Terminal::info('Operation cancelled.');

            return;
        }

        $confirmation2 = strtolower(readline("This is a destructive action. Please type 'yes' again to confirm: "));
        if ($confirmation2 !== 'yes') {
            Terminal::info('Operation cancelled.');

            return;
        }

        foreach ($taskFiles as $taskFile) {
            unlink($taskFile);
        }

        Terminal::success('Cron tasks cleared!');
    }
}

$registry->register(new ClearCronCommand());
