<?php

class Security
{
    // حماية ضد XSS فقط
    public static function sanitizeInput($data)
    {
        // إزالة أي وسم HTML غير مرغوب فيه
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // إزالة أي سكربتات أو أكواد JavaScript
        $data = preg_replace('/<script.*?<\/script>/is', '', $data);

        return $data;
    }

    // دالة للتحقق والتصفية
    public static function validateAndSanitize($input, $type)
    {
        if ($type === 'xss') {
            return self::sanitizeInput($input);
        }

        return $input;
    }
}
