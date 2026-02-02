<?php

class FirewallTest extends TestCase {
    public function testFirewallAllowed() {
        // Mock config
        $configPath = PROJECT_ROOT . '/Json/firewall.json';
        if (!is_dir(dirname($configPath))) mkdir(dirname($configPath), 0777, true);
        file_put_contents($configPath, json_encode([
            'block_ips' => ['1.2.3.4'],
            'block_user_agents' => ['BadBot'],
            'redirect_blocked_to' => 'blocked'
        ]));

        $_SERVER['REMOTE_ADDR'] = '192.168.1.1';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';

        // Should not exit
        Firewall::check();
        $this->assertTrue(true); // If we reach here, it didn't block
    }
}
