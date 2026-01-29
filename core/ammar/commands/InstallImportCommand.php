<?php

class InstallImportCommand extends Command {
    public function __construct() {
        parent::__construct('install:import', 'Install a new library');
    }

    public function execute($args) {
        $link = $args['1'] ?? readline("1- Enter Git repository URL: ");
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            Terminal::error("Invalid URL provided!");
            return;
        }

        $repoName = basename(parse_url($link, PHP_URL_PATH), '.git');

        $importDir = PROJECT_ROOT . '/core/import';
        $repoDir = $importDir . '/' . $repoName;

        if (!is_dir($importDir)) {
            mkdir($importDir, 0755, true);
        }

        if (is_dir($repoDir)) {
            Terminal::warning("Repository directory already exists: " . $repoName);
            $confirmation = strtolower(readline("Overwrite? (yes/no): "));
            if ($confirmation !== 'yes') {
                Terminal::info("Operation cancelled.");
                return;
            }
            // Logic to delete existing dir would go here if needed,
            // but git clone will fail if dir is not empty.
        }

        Terminal::info("Cloning repository " . Terminal::color($link, 'cyan') . "...");

        exec("git clone $link $repoDir", $output, $returnVar);

        if ($returnVar !== 0) {
            Terminal::error("Error cloning repository!");
            return;
        }

        // Update package.json
        $packageJsonPath = $importDir . '/package.json';
        $packageData = [];

        if (file_exists($packageJsonPath)) {
            $packageData = json_decode(file_get_contents($packageJsonPath), true) ?? [];
        }

        $packageData['dependencies'][$repoName] = $link;

        file_put_contents($packageJsonPath, json_encode($packageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        Terminal::success("Repository cloned successfully to " . Terminal::color("core/import/$repoName", 'cyan'));
        Terminal::success("package.json updated.");
    }
}

$registry->register(new InstallImportCommand());
