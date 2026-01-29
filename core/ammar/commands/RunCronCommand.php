<?php

class RunCronCommand extends Command
{
    public function __construct()
    {
        parent::__construct('run:cron', 'Manually run a specific cron task');
    }

    public function execute($args)
    {
        $taskNameInput = $args['1'] ?? readline("1- What's the Task Name? ");
        if (!$taskNameInput) {
            Terminal::error('Task name is required.');

            return;
        }

        $taskName = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $taskNameInput));

        if (empty($taskName)) {
            Terminal::error('Invalid task name provided after sanitization.');

            return;
        }

        $taskFilePath = CRON_TASKS_DIR.$taskName.'.php';

        if (!file_exists($taskFilePath)) {
            Terminal::error("Cron task file '".Terminal::color($taskName.'.php', 'red')."' does not exist.");

            return;
        }

        $cronRunnerPath = PROJECT_ROOT.'/core/cron/cron_runner.php';

        if (!file_exists($cronRunnerPath)) {
            Terminal::error('Main cron runner script not found at '.$cronRunnerPath);

            return;
        }

        $phpExecutable = PHP_BINARY ?: 'php';
        $execCommand = escapeshellcmd($phpExecutable).' '.escapeshellarg($cronRunnerPath).' '.escapeshellarg($taskName);

        Terminal::info('Executing cron task: '.Terminal::color($taskName, 'cyan'));

        passthru($execCommand, $return_var);

        if ($return_var !== 0) {
            Terminal::error("Cron task '{$taskName}' encountered an error (exit code: $return_var).");
        } else {
            Terminal::success("Cron task '{$taskName}' finished.");
        }
    }
}

$registry->register(new RunCronCommand());
