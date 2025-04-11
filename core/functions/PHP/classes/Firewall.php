<?php

class Firewall
{
    public static function check()
    {
        $configPath = __DIR__ . "/../../../../Json/firewall.json";

        if (!file_exists($configPath)) {
            return; // لا توجد إعدادات
        }

        $config = json_decode(file_get_contents($configPath), true);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

        // فحص IP
        if (!empty($config['block_ips']) && in_array($ip, $config['block_ips'])) {
            self::block($config);
        }

        // فحص User-Agent
        if (!empty($config['block_user_agents'])) {
            foreach ($config['block_user_agents'] as $ua) {
                if (strpos($agent, strtolower($ua)) !== false) {
                    self::block($config);
                }
            }
        }
    }

    private static function block($config)
    {
        $redirectTo = getEnvValue('WEBSITE_URL') . $config['redirect_blocked_to'] ?? 'blocked';
        header("Location: $redirectTo");
        exit;
    }
}
