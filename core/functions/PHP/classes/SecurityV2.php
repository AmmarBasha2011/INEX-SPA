<?php

class SecurityV2
{
    /**
     * Sanitize a string by removing tags and trimming whitespace.
     *
     * @param string $input The input string.
     *
     * @return string The sanitized string.
     */
    public static function sanitizeString(string $input): string
    {
        return strip_tags(trim($input));
    }

    /**
     * Sanitize input to ensure it is an integer.
     *
     * @param string $input The input string.
     *
     * @return int The sanitized integer.
     */
    public static function sanitizeInt(string $input): int
    {
        return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize an email address.
     *
     * @param string $input The input string.
     *
     * @return string The sanitized email.
     */
    public static function sanitizeEmail(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize a URL.
     *
     * @param string $input The input string.
     *
     * @return string The sanitized URL.
     */
    public static function sanitizeUrl(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_URL);
    }

    /**
     * Validate an email address.
     *
     * @param string $email The email to validate.
     *
     * @return bool
     */
    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate a URL.
     *
     * @param string $url The URL to validate.
     *
     * @return bool
     */
    public static function isUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Check if a string contains only alphanumeric characters.
     *
     * @param string $string The string to check.
     *
     * @return bool
     */
    public static function isAlphaNumeric(string $string): bool
    {
        return ctype_alnum($string);
    }

    /**
     * Check if the current request is a POST request.
     *
     * @return bool
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if the current request is a GET request.
     *
     * @return bool
     */
    public static function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Escape HTML entities in a string.
     *
     * @param string $string The string to escape.
     *
     * @return string
     */
    public static function escapeHtml(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Check password strength.
     * (e.g., at least 8 characters, one uppercase, one lowercase, one number).
     *
     * @param string $password The password to check.
     *
     * @return bool
     */
    public static function checkPasswordStrength(string $password): bool
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Generate and store a CSRF token in the session.
     *
     * @return string The generated token.
     */
    public static function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    /**
     * Validate a CSRF token.
     *
     * @param string $token The token from the user's request.
     *
     * @return bool
     */
    public static function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Set common security headers.
     */
    public static function setSecurityHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline';");
    }

    /**
     * Check if the current request is over HTTPS.
     *
     * @return bool
     */
    public static function isSecureRequest(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    /**
     * Generate a numeric One-Time Password (OTP).
     *
     * @param int $length The length of the OTP.
     *
     * @return string
     */
    public static function generateOtp(int $length = 6): string
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }

        return $otp;
    }

    /**
     * A basic filter to prevent SQL injection.
     * Note: It's always better to use prepared statements.
     *
     * @param string $input The string to filter.
     *
     * @return string
     */
    public static function filterSqlInjection(string $input): string
    {
        return preg_replace('/[\'"]/', '', $input);
    }

    /**
     * Log a security event.
     *
     * @param string $message The message to log.
     */
    public static function logSecurityEvent(string $message): void
    {
        // Assumes a Logger class or a log file path is configured
        // For example: Logger::log('SECURITY: ' . $message);
        error_log('SECURITY EVENT: '.$message);
    }

    /**
     * Enforce HTTPS by redirecting HTTP requests.
     */
    public static function enforceHttps(): void
    {
        if (!self::isSecureRequest()) {
            header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
            exit;
        }
    }

    /**
     * Get the client's IP address safely.
     *
     * @return string
     */
    public static function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Starts a session with secure settings.
     */
    public static function startSecureSession(): void
    {
        session_start([
            'cookie_httponly' => true,
            'cookie_secure'   => self::isSecureRequest(),
            'cookie_samesite' => 'Lax',
        ]);
    }

    /**
     * Regenerates the session ID to prevent session fixation.
     */
    public static function regenerateSessionId(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /**
     * Sets a secure cookie.
     *
     * @param string $name   The name of the cookie.
     * @param string $value  The value of the cookie.
     * @param int    $expire The expiration timestamp.
     */
    public static function setSecureCookie(string $name, string $value, int $expire): void
    {
        setcookie($name, $value, [
            'expires'  => $expire,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'],
            'secure'   => self::isSecureRequest(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    /**
     * Validates an IP address.
     *
     * @param string $ip The IP address to validate.
     *
     * @return bool
     */
    public static function validateIpAddress(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Checks if an IP is in a private range (e.g., 192.168.x.x).
     *
     * @param string $ip The IP to check.
     *
     * @return bool
     */
    public static function isPrivateIp(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * Prevents directory traversal attacks on a file path.
     *
     * @param string $path The path to check.
     *
     * @return string|null The real path if safe, or null if traversal is detected.
     */
    public static function preventDirectoryTraversal(string $path): ?string
    {
        $realPath = realpath($path);
        // Define the base directory relative to this file's location
        $baseDir = realpath(__DIR__.'/../../../../');
        if ($realPath === false || strpos($realPath, $baseDir) !== 0) {
            return null;
        }

        return $realPath;
    }

    /**
     * Simple rate limiting check using sessions.
     *
     * @param string $key    A unique key for the action being limited.
     * @param int    $limit  The number of allowed requests.
     * @param int    $period The time period in seconds.
     *
     * @return bool True if the limit is exceeded, false otherwise.
     */
    public static function checkRequestRate(string $key, int $limit = 10, int $period = 60): bool
    {
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }
        $currentTime = time();
        // Clean up old timestamps
        $_SESSION['rate_limit'][$key] = array_filter($_SESSION['rate_limit'][$key] ?? [], function ($timestamp) use ($currentTime, $period) {
            return ($currentTime - $timestamp) < $period;
        });
        if (count($_SESSION['rate_limit'][$key]) >= $limit) {
            return true; // Limit exceeded
        }
        $_SESSION['rate_limit'][$key][] = $currentTime;

        return false;
    }

    /**
     * Checks if the request origin matches the host.
     *
     * @return bool
     */
    public static function verifyRequestOrigin(): bool
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);

            return $origin === $_SERVER['HTTP_HOST'];
        }

        return true; // No origin header present
    }

    /**
     * Generates a Hash-based Message Authentication Code (HMAC).
     *
     * @param string $data The data to be hashed.
     * @param string $key  The secret key.
     *
     * @return string
     */
    public static function generateHmac(string $data, string $key): string
    {
        return hash_hmac('sha256', $data, $key);
    }

    /**
     * Verifies an HMAC.
     *
     * @param string $data The original data.
     * @param string $hmac The HMAC to verify.
     * @param string $key  The secret key.
     *
     * @return bool
     */
    public static function verifyHmac(string $data, string $hmac, string $key): bool
    {
        return hash_equals(self::generateHmac($data, $key), $hmac);
    }

    /**
     * Validate that a string is valid JSON.
     *
     * @param string $json The string to validate.
     *
     * @return bool
     */
    public static function validateJson(string $json): bool
    {
        json_decode($json);

        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate a date string against a specific format.
     *
     * @param string $date   The date string.
     * @param string $format The expected format.
     *
     * @return bool
     */
    public static function validateDate(string $date, string $format = 'Y-m-d H:i:s'): bool
    {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    /**
     * Generate a UUID v4.
     *
     * @return string
     */
    public static function generateUuidV4(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0x0FFF) | 0x4000,
            random_int(0, 0x3FFF) | 0x8000,
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF)
        );
    }

    /**
     * A more comprehensive XSS filter.
     *
     * @param string $input The string to filter.
     *
     * @return string
     */
    public static function comprehensiveXssFilter(string $input): string
    {
        // This is a simplified example. A library like HTML Purifier is recommended for production.
        $input = self::escapeHtml($input);
        $input = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
        $input = preg_replace('#(on[a-z]+)=([\'"])(.*?)\\2#i', '', $input);

        return $input;
    }

    /**
     * Generate a Content Security Policy header string.
     *
     * @param array $directives Associative array of CSP directives.
     *
     * @return string
     */
    public static function generateCspHeader(array $directives): string
    {
        $header = '';
        foreach ($directives as $directive => $sources) {
            $header .= $directive.' '.implode(' ', $sources).'; ';
        }

        return trim($header);
    }

    /**
     * Apply a Content Security Policy header.
     *
     * @param array $directives
     */
    public static function applyCspHeader(array $directives): void
    {
        $headerValue = self::generateCspHeader($directives);
        header('Content-Security-Policy: '.$headerValue);
    }

    /**
     * Validate a file upload for security.
     *
     * @param array $file             The $_FILES['input_name'] array.
     * @param array $allowedMimeTypes e.g., ['image/jpeg', 'image/png']
     * @param int   $maxSize          In bytes.
     *
     * @return bool
     */
    public static function validateFileUpload(array $file, array $allowedMimeTypes, int $maxSize): bool
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false; // Handle upload error
        }
        if ($file['size'] > $maxSize) {
            return false; // File too large
        }
        if (!in_array(mime_content_type($file['tmp_name']), $allowedMimeTypes)) {
            return false; // Invalid file type
        }

        return true;
    }

    /**
     * Checks if a string is a valid UUID.
     *
     * @param string $uuid The string to check.
     *
     * @return bool
     */
    public static function isUuid(string $uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }

    /**
     * Compare two strings in constant time to prevent timing attacks.
     *
     * @param string $str1
     * @param string $str2
     *
     * @return bool
     */
    public static function timingSafeEquals(string $str1, string $str2): bool
    {
        return hash_equals($str1, $str2);
    }

    /**
     * Remove invisible characters from a string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeInvisibleCharacters(string $string): string
    {
        return preg_replace('/[\x00-\x1F\x7F]/u', '', $string);
    }

    /**
     * Validate that a string contains only letters (and optionally spaces).
     *
     * @param string $string
     * @param bool   $allowSpaces
     *
     * @return bool
     */
    public static function isAlpha(string $string, bool $allowSpaces = false): bool
    {
        if ($allowSpaces) {
            return (bool) preg_match('/^[a-zA-Z\s]+$/', $string);
        }

        return ctype_alpha($string);
    }

    /**
     * Validate that a string contains only letters and numbers.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isAlphaNumericStrict(string $string): bool
    {
        return ctype_alnum($string);
    }

    /**
     * Validate integer value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isInteger($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate float value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isFloat($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    /**
     * Get a value from the session safely.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function getSessionValue(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a value in the session.
     *
     * @param string $key
     * @param mixed  $value
     */
    public static function setSessionValue(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset a session value.
     *
     * @param string $key
     */
    public static function unsetSessionValue(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the current session completely.
     */
    public static function destroySession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
            session_destroy();
        }
    }

    /**
     * Check for common insecure direct object reference patterns.
     * For example, ensuring a user can only access their own resources.
     *
     * @param int $ownerId       The ID of the resource's owner.
     * @param int $currentUserId The ID of the user making the request.
     *
     * @return bool
     */
    public static function checkOwnership(int $ownerId, int $currentUserId): bool
    {
        return $ownerId === $currentUserId;
    }

    /**
     * Generate a random password with specified complexity.
     *
     * @param int  $length
     * @param bool $useUppercase
     * @param bool $useNumbers
     * @param bool $useSymbols
     *
     * @return string
     */
    public static function generateRandomPassword(int $length = 12, bool $useUppercase = true, bool $useNumbers = true, bool $useSymbols = true): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        if ($useUppercase) {
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($useNumbers) {
            $chars .= '0123456789';
        }
        if ($useSymbols) {
            $chars .= '!@#$%^&*()_+-=[]{}|';
        }

        $password = '';
        $charLength = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charLength)];
        }

        return $password;
    }

    /**
     * Validate a credit card number using the Luhn algorithm.
     *
     * @param string $cardNumber
     *
     * @return bool
     */
    public static function validateCreditCard(string $cardNumber): bool
    {
        $sum = 0;
        $numDigits = strlen($cardNumber);
        $parity = $numDigits % 2;
        for ($i = 0; $i < $numDigits; $i++) {
            $digit = $cardNumber[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
            }
            if ($digit > 9) {
                $digit -= 9;
            }
            $sum += $digit;
        }

        return ($sum % 10) == 0;
    }

    /**
     * Check if a string is a valid hexadecimal value.
     *
     * @param string $hex
     *
     * @return bool
     */
    public static function isHex(string $hex): bool
    {
        return ctype_xdigit($hex);
    }

    /**
     * Check if a string is a valid base64 encoded string.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isBase64(string $string): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string);
    }

    /**
     * Set the Referrer-Policy header.
     *
     * @param string $policy
     */
    public static function setReferrerPolicyHeader(string $policy = 'no-referrer-when-downgrade'): void
    {
        header('Referrer-Policy: '.$policy);
    }

    /**
     * Set the Permissions-Policy header (formerly Feature-Policy).
     *
     * @param array $directives
     */
    public static function setPermissionsPolicyHeader(array $directives): void
    {
        $header = '';
        foreach ($directives as $directive => $sources) {
            $header .= $directive.' '.(is_array($sources) ? implode(' ', $sources) : $sources).'; ';
        }
        header('Permissions-Policy: '.trim($header));
    }

    /**
     * Validate a domain name.
     *
     * @param string $domain
     *
     * @return bool
     */
    public static function validateDomain(string $domain): bool
    {
        return preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain) //valid chars
                && preg_match('/^.{1,253}$/', $domain) //overall length
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain); //length of each label
    }

    /**
     * Generate a random alphanumeric string.
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomString(int $length = 16): string
    {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / 62))), 1, $length);
    }

    /**
     * Limit login attempts for a given key (e.g., username or IP).
     *
     * @param string $key
     * @param int    $maxAttempts
     * @param int    $lockoutTime in seconds
     *
     * @return bool True if attempts are exceeded, false otherwise.
     */
    public static function limitLoginAttempts(string $key, int $maxAttempts = 5, int $lockoutTime = 300): bool
    {
        self::startSecureSession();
        $attempts = $_SESSION['login_attempts'][$key] ?? ['count' => 0, 'time' => time()];
        if (($attempts['count'] >= $maxAttempts) && (time() - $attempts['time'] < $lockoutTime)) {
            return true;
        }
        if (time() - $attempts['time'] > $lockoutTime) {
            $attempts = ['count' => 0, 'time' => time()]; // Reset on new attempt after lockout
        }
        $attempts['count']++;
        $_SESSION['login_attempts'][$key] = $attempts;

        return false;
    }

    /**
     * Reset login attempts for a given key.
     *
     * @param string $key
     */
    public static function resetLoginAttempts(string $key): void
    {
        unset($_SESSION['login_attempts'][$key]);
    }

    /**
     * Sanitize a filename to prevent directory traversal and other attacks.
     *
     * @param string $filename
     *
     * @return string
     */
    public static function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-zA-Z0-9\._-]/', '', basename($filename));
    }

    /**
     * Prevent clickjacking by setting the X-Frame-Options header.
     */
    public static function preventClickjacking(): void
    {
        header('X-Frame-Options: DENY');
    }

    /**
     * Validate an IPv4 address.
     *
     * @param string $ip
     *
     * @return bool
     */
    public static function validateIpv4(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Validate an IPv6 address.
     *
     * @param string $ip
     *
     * @return bool
     */
    public static function validateIpv6(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Strip null bytes from a string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function stripNullBytes(string $string): string
    {
        return str_replace(chr(0), '', $string);
    }

    /**
     * Check if a string contains only ASCII characters.
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isAscii(string $string): bool
    {
        return mb_check_encoding($string, 'ASCII');
    }

    /**
     * Sanitize a path to remove directory traversal characters.
     *
     * @param string $path
     *
     * @return string
     */
    public static function sanitizePath(string $path): string
    {
        return str_replace(['../', '..\\'], '', $path);
    }

    /**
     * Check if a string is a serialized string.
     *
     * @param string $data
     *
     * @return bool
     */
    public static function isSerialized(string $data): bool
    {
        if (trim($data) === '') {
            return false;
        }

        return @unserialize($data) !== false || $data === 'b:0;';
    }

    /**
     * Generate a cryptographic nonce.
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateNonce(int $length = 16): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Set Expect-CT header to enforce Certificate Transparency.
     *
     * @param int         $maxAge
     * @param bool        $enforce
     * @param string|null $reportUri
     */
    public static function setExpectCtHeader(int $maxAge = 86400, bool $enforce = false, ?string $reportUri = null): void
    {
        $header = "max-age={$maxAge}";
        if ($enforce) {
            $header .= '; enforce';
        }
        if ($reportUri) {
            $header .= "; report-uri=\"{$reportUri}\"";
        }
        header('Expect-CT: '.$header);
    }

    /**
     * Remove all script tags from a string.
     *
     * @param string $html
     *
     * @return string
     */
    public static function stripScriptTags(string $html): string
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    }

    /**
     * Verify that the user agent has not changed during a session.
     *
     * @return bool
     */
    public static function verifyUserAgent(): bool
    {
        self::startSecureSession();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $userAgent) {
            return false;
        }
        $_SESSION['user_agent'] = $userAgent;

        return true;
    }

    /**
     * Validate an IBAN number.
     *
     * @param string $iban
     *
     * @return bool
     */
    public static function validateIban(string $iban): bool
    {
        // Basic IBAN validation, for more robust validation a library might be needed
        $iban = strtolower(str_replace(' ', '', $iban));
        $countries = ['al', 'ad', 'at', 'az', 'bh', 'by', 'be', 'ba', 'br', 'bg', 'cr', 'hr', 'cy', 'cz', 'dk', 'do', 'sv', 'ee', 'fo', 'fi', 'fr', 'ge', 'de', 'gi', 'gr', 'gt', 'hu', 'is', 'ie', 'il', 'it', 'jo', 'kz', 'kw', 'lv', 'lb', 'li', 'lt', 'lu', 'mk', 'mt', 'mr', 'mu', 'mc', 'md', 'me', 'nl', 'no', 'pk', 'ps', 'pl', 'pt', 'qa', 'ro', 'sm', 'sa', 'rs', 'sk', 'si', 'es', 'se', 'ch', 'tn', 'tr', 'ae', 'gb', 'vg'];
        if (!in_array(substr($iban, 0, 2), $countries)) {
            return false;
        }
        $iban = substr($iban, 4).substr($iban, 0, 4);
        $iban = strtr($iban, 'abcdefghijklmnopqrstuvwxyz', '1011121314151617181920212223242526');

        return bcmod($iban, '97') === '1';
    }

    /**
     * Generate a file checksum.
     *
     * @param string $filePath
     * @param string $algo
     *
     * @return string|false
     */
    public static function generateFileChecksum(string $filePath, string $algo = 'sha256')
    {
        if (!file_exists($filePath)) {
            return false;
        }

        return hash_file($algo, $filePath);
    }

    /**
     * Verify a file checksum.
     *
     * @param string $filePath
     * @param string $expectedChecksum
     * @param string $algo
     *
     * @return bool
     */
    public static function verifyFileChecksum(string $filePath, string $expectedChecksum, string $algo = 'sha256'): bool
    {
        $actualChecksum = self::generateFileChecksum($filePath, $algo);

        return $actualChecksum && hash_equals($expectedChecksum, $actualChecksum);
    }

    /**
     * Disallow XML external entity loading to prevent XXE attacks.
     */
    public static function disableXmlExternalEntities(): void
    {
        libxml_disable_entity_loader(true);
    }

    /**
     * Securely parse an XML string.
     *
     * @param string $xmlString
     *
     * @return SimpleXMLElement|false
     */
    public static function secureParseXml(string $xmlString)
    {
        self::disableXmlExternalEntities();

        return simplexml_load_string($xmlString);
    }

    /**
     * Check if a password has been exposed in a data breach (requires an API like Have I Been Pwned).
     * This is a conceptual example.
     *
     * @param string $password
     *
     * @return bool True if exposed, false otherwise.
     */
    public static function isPasswordPwned(string $password): bool
    {
        $sha1Password = strtoupper(sha1($password));
        $prefix = substr($sha1Password, 0, 5);
        $suffix = substr($sha1Password, 5);

        $response = @file_get_contents('https://api.pwnedpasswords.com/range/'.$prefix);
        if ($response === false) {
            return false; // Could not check
        }

        return strpos($response, $suffix) !== false;
    }

    /**
     * Generate a strong, random key.
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateEncryptionKey(int $length = 32): string
    {
        return random_bytes($length);
    }

    /**
     * Set a timeout for the current session.
     *
     * @param int $timeoutSeconds
     *
     * @return bool True if session is still valid, false if timed out.
     */
    public static function setSessionTimeout(int $timeoutSeconds = 1800): bool
    {
        self::startSecureSession();
        $currentTime = time();
        if (isset($_SESSION['last_activity']) && ($currentTime - $_SESSION['last_activity'] > $timeoutSeconds)) {
            self::destroySession();

            return false;
        }
        $_SESSION['last_activity'] = $currentTime;

        return true;
    }

    /**
     * Sanitize a string for use in a LIKE query.
     *
     * @param string $string
     *
     * @return string
     */
    public static function sanitizeForLike(string $string): string
    {
        return str_replace(['%', '_'], ['\%', '\_'], $string);
    }

    /**
     * Check if a given string is a valid username format.
     * (e.g., 3-20 characters, alphanumeric and underscores).
     *
     * @param string $username
     *
     * @return bool
     */
    public static function validateUsername(string $username): bool
    {
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }

    /**
     * Validate a US phone number format.
     *
     * @param string $phone
     *
     * @return bool
     */
    public static function validateUsPhone(string $phone): bool
    {
        return preg_match('/^(\+1\s?)?\(?[2-9][0-8][0-9]\)?[\s.-]?[2-9][0-9]{2}[\s.-]?[0-9]{4}$/', $phone);
    }

    /**
     * Securely read from a file.
     *
     * @param string $path
     *
     * @return string|false
     */
    public static function secureReadFile(string $path)
    {
        $safePath = self::preventDirectoryTraversal($path);
        if ($safePath && is_readable($safePath)) {
            return file_get_contents($safePath);
        }

        return false;
    }

    /**
     * Securely write to a file.
     *
     * @param string $path
     * @param string $data
     *
     * @return int|false
     */
    public static function secureWriteFile(string $path, string $data)
    {
        $safePath = self::preventDirectoryTraversal($path);
        if ($safePath) {
            return file_put_contents($safePath, $data, LOCK_EX);
        }

        return false;
    }

    /**
     * Obfuscate an email address.
     *
     * @param string $email
     *
     * @return string
     */
    public static function obfuscateEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        return substr($name, 0, 2).str_repeat('*', strlen($name) - 2).'@'.$domain;
    }

    /**
     * Check if a string is a valid time format (HH:MM:SS).
     *
     * @param string $time
     *
     * @return bool
     */
    public static function validateTime(string $time): bool
    {
        return preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $time);
    }

    /**
     * Validate a MAC address.
     *
     * @param string $mac
     *
     * @return bool
     */
    public static function validateMacAddress(string $mac): bool
    {
        return filter_var($mac, FILTER_VALIDATE_MAC) !== false;
    }

    /**
     * Set a Cross-Origin-Opener-Policy header.
     *
     * @param string $policy
     */
    public static function setCoopHeader(string $policy = 'same-origin'): void
    {
        header('Cross-Origin-Opener-Policy: '.$policy);
    }

    /**
     * Set a Cross-Origin-Embedder-Policy header.
     *
     * @param string $policy
     */
    public static function setCoepHeader(string $policy = 'require-corp'): void
    {
        header('Cross-Origin-Embedder-Policy: '.$policy);
    }

    /**
     * Set a Cross-Origin-Resource-Policy header.
     *
     * @param string $policy
     */
    public static function setCorpHeader(string $policy = 'same-origin'): void
    {
        header('Cross-Origin-Resource-Policy: '.$policy);
    }

    /**
     * Generate a cryptographically secure random integer within a range.
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public static function secureRandomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    /**
     * Validate that a string has a minimum length.
     *
     * @param string $string
     * @param int    $minLength
     *
     * @return bool
     */
    public static function validateMinLength(string $string, int $minLength): bool
    {
        return mb_strlen($string) >= $minLength;
    }

    /**
     * Validate that a string has a maximum length.
     *
     * @param string $string
     * @param int    $maxLength
     *
     * @return bool
     */
    public static function validateMaxLength(string $string, int $maxLength): bool
    {
        return mb_strlen($string) <= $maxLength;
    }

    /**
     * Validate that a number is within a specific range.
     *
     * @param int|float $number
     * @param int|float $min
     * @param int|float $max
     *
     * @return bool
     */
    public static function validateRange($number, $min, $max): bool
    {
        return $number >= $min && $number <= $max;
    }

    /**
     * Get the current URL safely.
     *
     * @return string
     */
    public static function getCurrentUrl(): string
    {
        $protocol = self::isSecureRequest() ? 'https' : 'http';

        return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    /**
     * Check if a session has been initiated.
     *
     * @return bool
     */
    public static function isSessionStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Enforce a password policy.
     *
     * @param string $password
     * @param array  $policy   e.g., ['minLength' => 8, 'requireUppercase' => true, 'requireNumber' => true, 'requireSymbol' => true]
     *
     * @return bool
     */
    public static function enforcePasswordPolicy(string $password, array $policy): bool
    {
        if (isset($policy['minLength']) && strlen($password) < $policy['minLength']) {
            return false;
        }
        if (isset($policy['requireUppercase']) && !preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (isset($policy['requireLowercase']) && !preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (isset($policy['requireNumber']) && !preg_match('/[0-9]/', $password)) {
            return false;
        }
        if (isset($policy['requireSymbol']) && !preg_match('/[\W_]/', $password)) {
            return false;
        } // \W is any non-word character

        return true;
    }

    /**
     * Validate a given timezone identifier.
     *
     * @param string $timezone
     *
     * @return bool
     */
    public static function validateTimezone(string $timezone): bool
    {
        return in_array($timezone, timezone_identifiers_list());
    }

    /**
     * Remove the UTF-8 Byte Order Mark (BOM) from a string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeBom(string $string): string
    {
        if (substr($string, 0, 3) == pack('CCC', 0xEF, 0xBB, 0xBF)) {
            return substr($string, 3);
        }

        return $string;
    }
}
