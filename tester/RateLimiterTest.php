<?php

class RateLimiterTest extends TestCase {
    public function testRateLimiting() {
        // Clean start
        $storageFile = __DIR__.'/../core/storage/rate_limit.json';
        if (file_exists($storageFile)) unlink($storageFile);

        $ip = '127.0.0.1';
        // Should work 5 times
        for ($i = 0; $i < 5; $i++) {
            RateLimiter::check($ip);
        }

        $data = json_decode(file_get_contents($storageFile), true);
        $this->assertEquals(5, $data[$ip]['count']);
    }
}
