<?php

/**
 * A simple firewall class to block requests based on IP and User-Agent.
 */
class Firewall
{
    /**
     * Checks the current request against the firewall rules.
     */
    public static function check()
    {
        $configPath = __DIR__.'/../../../../Json/firewall.json';

        if (!file_exists($configPath)) {
            return;
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    /**
     * Blocks the current request and redirects to the blocked page.
     *
     * @param array $config The firewall configuration.
     */
    private static function block($config)
    {
        $redirectTo = getEnvValue('WEBSITE_URL').$config['redirect_blocked_to'] ?? 'blocked';
        header("Location: $redirectTo");
        exit;
    }
}
