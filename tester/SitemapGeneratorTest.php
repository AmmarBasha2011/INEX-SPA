<?php

class SitemapGeneratorTest extends TestCase {
    public function testGenerate() {
        // Create a dummy route
        $webDir = PROJECT_ROOT . '/web';
        if (!is_dir($webDir)) mkdir($webDir, 0777, true);
        file_put_contents($webDir . '/test_page.ahmed.php', '');

        $publicDir = PROJECT_ROOT . '/public';
        if (!is_dir($publicDir)) mkdir($publicDir, 0777, true);

        SitemapGenerator::generate();

        $sitemapFile = $publicDir . '/sitemap.xml';
        $this->assertTrue(file_exists($sitemapFile));
        $content = file_get_contents($sitemapFile);
        $this->assertTrue(str_contains($content, '<loc>http://localhost:8000/test_page</loc>'));

        unlink($webDir . '/test_page.ahmed.php');
    }
}
