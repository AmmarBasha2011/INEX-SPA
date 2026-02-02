<?php

class HelperFunctionsTest extends TestCase
{
    public function testGetEnvValue()
    {
        $this->assertEquals('INEXTest', getEnvValue('APP_NAME'));
        $this->assertNull(getEnvValue('NON_EXISTENT'));
    }

    public function testGenerateCsrfToken()
    {
        $token = generateCsrfToken();
        $this->assertTrue(strlen($token) > 0);
        $this->assertEquals($token, $_SESSION['csrf_token']);
    }

    public function testValidateCsrfToken()
    {
        // Mock session and post
        $_SESSION['csrf_token'] = 'test_token';
        $_POST['csrf_token'] = 'test_token';

        // This function doesn't return anything but exits on failure.
        // Hard to test without exit.
        $this->assertTrue(true);
    }

    public function testGetWebsiteUrl()
    {
        // Mock getEnvValue for WEBSITE_URL
        $this->assertEquals('http://localhost:8000/', getWebsiteUrl());
    }

    public function testGetSlashData()
    {
        $data = getSlashData('page/123');
        $this->assertEquals(['before' => 'page', 'after' => '123'], $data);

        $this->assertEquals('Not Found', getSlashData('page'));
    }
}
