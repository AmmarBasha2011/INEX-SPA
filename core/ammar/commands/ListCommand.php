<?php

class ListCommand extends Command {
    private $registry;

    public function __construct($registry) {
        parent::__construct('list', 'List all commands');
        $this->registry = $registry;
    }

    public function execute($args) {
        Terminal::header("INEX SPA Framework - Ammar CLI");
        echo " Usage: " . Terminal::color("php ammar <command> [options]", 'yellow') . PHP_EOL . PHP_EOL;

        $commands = $this->registry->getCommands();
        ksort($commands);

        $categories = [
            'list'   => [],
            'make'   => [],
            'get'    => [],
            'delete' => [],
            'clear'  => [],
            'run'    => [],
            'other'  => []
        ];

        foreach ($commands as $name => $cmd) {
            $parts = explode(':', $name);
            $cat = $parts[0];
            if (isset($categories[$cat])) {
                $categories[$cat][] = ['name' => $name, 'desc' => $cmd->getDescription()];
            } else {
                $categories['other'][] = ['name' => $name, 'desc' => $cmd->getDescription()];
            }
        }

        $maxLen = 0;
        foreach ($commands as $name => $cmd) {
            $maxLen = max($maxLen, strlen($name));
        }

        foreach ($categories as $catName => $catCmds) {
            if (empty($catCmds)) continue;

            echo Terminal::color(" " . strtoupper($catName), 'cyan') . PHP_EOL;
            foreach ($catCmds as $c) {
                echo "  " . Terminal::color(str_pad($c['name'], $maxLen + 2), 'green');
                echo $c['desc'] . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }
}
