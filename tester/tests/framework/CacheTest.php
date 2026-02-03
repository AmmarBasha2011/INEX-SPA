<?php

// tester/tests/framework/CacheTest.php

runTest('Cache::set and Cache::get - basic string', function () {
    Cache::set('test_key', 'test_value', 10);
    assertEquals('test_value', Cache::get('test_key'));
    Cache::delete('test_key');
});

runTest('Cache::set and Cache::get - array', function () {
    $data = ['a' => 1, 'b' => 2];
    Cache::set('array_key', $data, 10);
    assertEquals($data, Cache::get('array_key'));
    Cache::delete('array_key');
});

runTest('Cache::get - expired', function () {
    Cache::set('expired_key', 'value', -10); // Already expired
    assertFalse(Cache::get('expired_key'));
});

runTest('Cache::get - non-existent', function () {
    assertFalse(Cache::get('non_existent'));
});

runTest('Cache::update - existing key', function () {
    Cache::set('update_key', 'old_value', 10);
    assertTrue(Cache::update('update_key', 'new_value'));
    assertEquals('new_value', Cache::get('update_key'));
    Cache::delete('update_key');
});

runTest('Cache::update - non-existing key', function () {
    assertFalse(Cache::update('no_key', 'value'));
});

runTest('Cache::delete', function () {
    Cache::set('delete_key', 'value', 10);
    Cache::delete('delete_key');
    assertFalse(Cache::get('delete_key'));
});

for ($i = 0; $i < 40; $i++) {
    runTest("Cache::set/get loop test $i", function () use ($i) {
        $key = "loop_key_$i";
        $val = "loop_val_$i";
        Cache::set($key, $val, 5);
        assertEquals($val, Cache::get($key));
        Cache::delete($key);
    });
}
