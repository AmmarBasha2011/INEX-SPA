<?php

class DeleteCronCommand extends Command {
    public function __construct() {
        parent::__construct('delete:cron', 'Delete an existing cron task file');
    }

    public function execute($args) {
        $taskNameInput = $args['1'] ?? readline("1- What's the Task Name? ");
        if (!$taskNameInput) {
            Terminal::error("Task name is required.");
            return;
        }

        $taskName = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $taskNameInput));

        if (empty($taskName)) {
            Terminal::error("Invalid task name provided after sanitization.");
            return;
        }

        $filePath = CRON_TASKS_DIR . $taskName . '.php';

        if (!file_exists($filePath)) {
            Terminal::error("Cron task file '" . Terminal::color($taskName . ".php", 'red') . "' not found.");
            return;
        }

        $confirmation = strtolower(readline("Are you sure you want to delete '" . Terminal::color($taskName . ".php", 'yellow') . "'? (yes/no): "));

        if ($confirmation !== 'yes') {
            Terminal::info("Deletion cancelled.");
            return;
        }

        if (unlink($filePath)) {
            Terminal::success("Cron task file deleted successfully.");
        } else {
            Terminal::error("Could not delete cron task file.");
        }
    }
}

$registry->register(new DeleteCronCommand());
