<?php

class ListCronCommand extends Command
{
    public function __construct()
    {
        parent::__construct('list:cron', 'List all available cron tasks');
    }

    public function execute($args)
    {
        Terminal::header('Available Cron Tasks');

        if (!is_dir(CRON_TASKS_DIR)) {
            if (!mkdir(CRON_TASKS_DIR, 0755, true) && !is_dir(CRON_TASKS_DIR)) {
                Terminal::error('Failed to create cron tasks directory.');

                return;
            }
        }

        $taskFiles = glob(CRON_TASKS_DIR.'*.php');

        if (empty($taskFiles)) {
            Terminal::warning("No cron tasks found in 'core/cron/tasks/'.");

            return;
        }

        foreach ($taskFiles as $taskFile) {
            $taskName = basename($taskFile, '.php');
            echo '  '.Terminal::color('→', 'cyan').' '.$taskName.PHP_EOL;
        }
        echo PHP_EOL;
    }
}

$registry->register(new ListCronCommand());
