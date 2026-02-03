<?php
// tester/tests/framework/SessionTest.php

runTest('Session::make and Session::get - basic string', function() {
    Session::make('user_name', 'Jules');
    assertEquals('Jules', Session::get('user_name'));
    Session::delete('user_name');
});

runTest('Session::make and Session::get - array', function() {
    $roles = ['admin', 'editor'];
    Session::make('user_roles', $roles);
    assertEquals($roles, Session::get('user_roles'));
    Session::delete('user_roles');
});

runTest('Session::get - non-existent', function() {
    assertEquals(null, Session::get('not_found'));
});

runTest('Session::delete', function() {
    Session::make('temp', 'data');
    Session::delete('temp');
    assertEquals(null, Session::get('temp'));
});

for ($i = 0; $i < 40; $i++) {
    runTest("Session::make/get loop test $i", function() use ($i) {
        $key = "sess_key_$i";
        $val = "sess_val_$i";
        Session::make($key, $val);
        assertEquals($val, Session::get($key));
        Session::delete($key);
    });
}
