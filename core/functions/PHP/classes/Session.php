<?php

class Session {
    private static $storagePath = __DIR__ . "/../../../storage/sessions/";
    
    public static function make($key, $value) {
        $data = self::encrypt(json_encode($value));
        file_put_contents(self::$storagePath . $key, $data);
    }

    public static function get($key) {
        $file = self::$storagePath . $key;
        if (!file_exists($file)) return null;
        return json_decode(self::decrypt(file_get_contents($file)), true);
    }

    public static function delete($key) {
        unlink(self::$storagePath . $key);
    }

    private static function encrypt($data) {
        return base64_encode($data); // Simple encryption (can be improved)
    }

    private static function decrypt($data) {
        return base64_decode($data);
    }
}
