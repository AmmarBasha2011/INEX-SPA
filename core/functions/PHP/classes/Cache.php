<?php

class Cache
{
    private static $cacheDir = __DIR__.'/../../../cache/';

    // تخزين البيانات في ملف
    public static function set($key, $data, $expiration = 3600)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        $content = json_encode([
            'expires' => time() + $expiration,
            'data'    => $data,
        ]);
        file_put_contents($file, $content);
    }

    // استرجاع البيانات
    public static function get($key)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false;
        }

        $content = json_decode(file_get_contents($file), true);
        if (time() > $content['expires']) {
            unlink($file);

            return false;
        }

        return $content['data'];
    }

    // تحديث البيانات بدون تغيير وقت انتهاء الصلاحية
    public static function update($key, $newData)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return false;
        } // لا يوجد كاش ليتم تحديثه

        $content = json_decode(file_get_contents($file), true);
        $content['data'] = $newData; // تحديث البيانات فقط

        file_put_contents($file, json_encode($content));

        return true;
    }

    // حذف الكاش
    public static function delete($key)
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
