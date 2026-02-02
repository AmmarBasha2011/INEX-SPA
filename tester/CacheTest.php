<?php

class CacheTest extends TestCase
{
    public function testSetAndGet()
    {
        Cache::set('test_key', 'test_value', 60);
        $this->assertEquals('test_value', Cache::get('test_key'));
    }

    public function testUpdate()
    {
        Cache::set('test_key', 'initial_value', 60);
        Cache::update('test_key', 'updated_value');
        $this->assertEquals('updated_value', Cache::get('test_key'));
    }

    public function testDelete()
    {
        Cache::set('test_key', 'test_value', 60);
        Cache::delete('test_key');
        $this->assertFalse(Cache::get('test_key'));
    }

    public function testExpiration()
    {
        Cache::set('exp_key', 'exp_value', -1); // Expired immediately
        $this->assertFalse(Cache::get('exp_key'));
    }
}
