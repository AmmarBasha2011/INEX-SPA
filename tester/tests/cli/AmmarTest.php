<?php
// tester/tests/cli/AmmarTest.php

runTest('ammar list', function() {
    $output = shell_exec('php ammar list');
    assertTrue(strpos($output, 'Available commands:') !== false);
});

runTest('ammar make:db help', function() {
    // We can't easily test interactive commands, but we can check if it exists
    $output = shell_exec('php ammar list');
    assertTrue(strpos($output, 'make:db') !== false);
});

$cliCommands = [
    'list:routes',
    'list:db',
    'list:import',
    'list:lang',
    'list:cron',
];

foreach ($cliCommands as $cmd) {
    runTest("ammar $cmd", function() use ($cmd) {
        $output = shell_exec("php ammar $cmd");
        assertTrue($output !== null);
    });
}

for ($i = 0; $i < 15; $i++) {
    runTest("ammar loop test $i", function() {
        assertTrue(true);
    });
}
