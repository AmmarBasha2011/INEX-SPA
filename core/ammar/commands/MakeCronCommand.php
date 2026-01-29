<?php

class MakeCronCommand extends Command {
    public function __construct() {
        parent::__construct('make:cron', 'Create a new cron task file');
    }

    public function execute($args) {
        $taskNameInput = $args['1'] ?? readline("1- What's the Task Name? ");
        if (!$taskNameInput) {
            Terminal::error("Task name is required!");
            return;
        }

        $taskName = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $taskNameInput));

        if (empty($taskName)) {
            Terminal::error("Invalid task name provided after sanitization.");
            return;
        }

        if (!preg_match('/^[a-zA-Z]/', $taskName)) {
            Terminal::error("Task name must start with a letter.");
            return;
        }

        $filePath = CRON_TASKS_DIR . $taskName . '.php';

        if (file_exists($filePath)) {
            Terminal::warning("Cron task file already exists!");
            return;
        }

        $fileContent = <<<PHP
<?php

class {$taskName} {
    public function handle() {
        if (!function_exists('log_cron_message')) {
             function log_cron_message(\$message, \$task = '{$taskName}') {
                \$logFile = dirname(__DIR__, 3) . '/logs/cron.log';
                \$timestamp = date('Y-m-d H:i:s');
                \$formattedMessage = "[\{\$timestamp}] [{\$task}] {\$message}" . PHP_EOL;
                if (!is_dir(dirname(\$logFile))) mkdir(dirname(\$logFile), 0755, true);
                file_put_contents(\$logFile, \$formattedMessage, FILE_APPEND);
            }
        }

        \$timestamp = date('Y-m-d H:i:s');
        \$outputMessage = "{$taskName} executed successfully at {\$timestamp}.";

        echo \$outputMessage . PHP_EOL;

        if (function_exists('log_cron_message')) {
            log_cron_message(\$outputMessage, '{$taskName}');
        }

        // TODO: Implement your cron task logic here.

        if (function_exists('log_cron_message')) {
            log_cron_message("{$taskName} finished.", '{$taskName}');
        }
    }
}
PHP;

        if (file_put_contents($filePath, $fileContent)) {
            chmod($filePath, 0664);
            Terminal::success("Cron task file created: " . Terminal::color($taskName . ".php", 'cyan'));
        } else {
            Terminal::error("Could not create cron task file!");
        }
    }
}

$registry->register(new MakeCronCommand());
