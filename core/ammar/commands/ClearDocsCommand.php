<?php

class ClearDocsCommand extends Command {
    public function __construct() {
        parent::__construct('clear:docs', 'Clear all docs');
    }

    public function execute($args) {
        $folderlist = [
            PROJECT_ROOT . '/about-me',
            PROJECT_ROOT . '/changelog',
            PROJECT_ROOT . '/inex-spa',
            PROJECT_ROOT . '/.gitbook'
        ];
        $filelist = [
            PROJECT_ROOT . '/README (1).md',
            PROJECT_ROOT . '/README.md',
            PROJECT_ROOT . '/SUMMARY.md',
            PROJECT_ROOT . '/LICENSE'
        ];

        foreach ($filelist as $file) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    Terminal::success("Deleted file: " . $file);
                } else {
                    Terminal::error("Failed to delete file: " . $file);
                }
            }
        }

        foreach ($folderlist as $folder) {
            if (is_dir($folder)) {
                Terminal::info("Cleaning folder: " . $folder);
                $this->deleteFolderRecursively($folder);
                if (rmdir($folder)) {
                    Terminal::success("Deleted root folder: " . $folder);
                } else {
                    Terminal::error("Failed to delete root folder: " . $folder);
                }
            }
        }

        Terminal::success("All docs have been processed!");
    }

    private function deleteFolderRecursively($folderPath) {
        if (!is_dir($folderPath)) return;

        $items = scandir($folderPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $folderPath . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->deleteFolderRecursively($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }
    }
}

$registry->register(new ClearDocsCommand());
