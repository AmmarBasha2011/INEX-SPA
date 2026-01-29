<?php

class DeleteImportCommand extends Command {
    public function __construct() {
        parent::__construct('delete:import', 'Delete an import');
    }

    public function execute($args) {
        $name = $args['1'] ?? readline("1- Enter import name to delete: ");
        if (!$name) {
            Terminal::error("Import name is required!");
            return;
        }

        $importDir = PROJECT_ROOT . '/core/import/' . $name;

        if (!is_dir($importDir)) {
            Terminal::error("Import not found: " . Terminal::color($name, 'red'));
            return;
        }

        // Confirmation prompt
        $confirmation = strtolower(readline("Are you sure you want to delete '{$name}'? (yes/no): "));
        if ($confirmation !== 'yes') {
            Terminal::info("Operation cancelled.");
            return;
        }

        // Function to remove directory recursively
        $deleteRecursively = function($dir) use (&$deleteRecursively) {
            if (!is_dir($dir)) return unlink($dir);
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') continue;
                if (!$deleteRecursively($dir . DIRECTORY_SEPARATOR . $item)) return false;
            }
            return rmdir($dir);
        };

        if ($deleteRecursively($importDir)) {
            Terminal::success("Import deleted: " . Terminal::color($name, 'cyan'));
        } else {
            Terminal::error("Failed to delete import directory!");
        }

        // Update package.json
        $packageJsonPath = PROJECT_ROOT . '/core/import/package.json';
        if (file_exists($packageJsonPath)) {
            $packageData = json_decode(file_get_contents($packageJsonPath), true) ?? [];
            if (isset($packageData['dependencies'][$name])) {
                unset($packageData['dependencies'][$name]);
                file_put_contents($packageJsonPath, json_encode($packageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                Terminal::success("package.json updated.");
            }
        }
    }
}

$registry->register(new DeleteImportCommand());
