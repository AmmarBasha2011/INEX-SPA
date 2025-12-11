<?php

/**
 * A simple firewall to block requests based on IP and User-Agent.
 *
 * This class reads a configuration file (`Json/firewall.json`) to check
 * incoming requests against a list of blocked IPs and User-Agent strings.
 */
class Firewall
{
    /**
     * Checks the current request against the firewall rules.
     *
     * If the client's IP address or User-Agent matches a rule in the
     * configuration, the request will be blocked.
     *
     * @return void
     */
    public static function check()
    {
        $configPath = __DIR__.'/../../../../Json/firewall.json';

        if (!file_exists($configPath)) {
            return; // No settings found
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        // Check IP
        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        // Check User-Agent
        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    /**
     * Blocks the request and redirects the user.
     *
     * This method is called when a firewall rule is triggered. It redirects
     * the user to the page specified in the firewall configuration.
     *
     * @param array $config The firewall configuration array.
     *
     * @return void
     */
    private static function block($config)
    {
        $redirectTo = getEnvValue('WEBSITE_URL').($config['redirect_blocked_to'] ?? 'blocked');
        header("Location: $redirectTo");
        exit;
    }
}
