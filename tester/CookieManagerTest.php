<?php

class CookieManagerTest extends TestCase {
    public function testGet() {
        $_COOKIE['test_cookie'] = 'test_value';
        $this->assertEquals('test_value', CookieManager::get('test_cookie'));
    }

    public function testExists() {
        $_COOKIE['test_cookie'] = 'test_value';
        $this->assertTrue(CookieManager::exists('test_cookie'));
        $this->assertFalse(CookieManager::exists('non_existent'));
    }

    public function testGetAll() {
        $_COOKIE = ['c1' => 'v1', 'c2' => 'v2'];
        $this->assertEquals(['c1' => 'v1', 'c2' => 'v2'], CookieManager::getAll());
    }
}
