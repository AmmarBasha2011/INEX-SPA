<?php

require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/classes/Database.php';
require_once 'core/functions/PHP/classes/UserAuth.php';
require_once 'core/functions/PHP/classes/Validation.php';

if (!function_exists('executeStatement')) {
    function executeStatement($sql, $params = [], $is_return = true)
    {
        $DB = new Database();

        return $DB->query($sql, $params, $is_return);
    }
}

// Setup environment for test
$originalEnv = file_exists('.env') ? file_get_contents('.env') : null;
file_put_contents('.env', "DB_DRIVER=sqlite\nDB_FILE=test_auth.sqlite\nUSE_AUTH=true\n");

// Clear database
if (file_exists('test_auth.sqlite')) {
    unlink('test_auth.sqlite');
}

// Create table
$sql = UserAuth::generateSQL();
executeStatement($sql, [], false);

$userDetails = [
    'name'        => 'Test User',
    'username'    => 'testuserhash',
    'email'       => 'testuserhash@gmail.com',
    'phoneNumber' => '+201111111112',
    'age'         => 25,
    'isCompany'   => 0,
    'domain'      => 'sub.test.com',
    'password'    => 'secure_password_123',
    'inviteCode'  => '123456',
];

$signUpResult = UserAuth::signUp($userDetails);
if ($signUpResult !== 'User successfully registered.') {
    echo "❌ signUp failed: $signUpResult\n";
    exit(1);
}

$users = executeStatement('SELECT * FROM users WHERE username = ?', ['testuserhash']);
$user = $users[0];

if ($user['password'] === 'secure_password_123') {
    echo "❌ SECURITY FAILURE: Password stored in plaintext!\n";
    exit(1);
}

if (!password_verify('secure_password_123', $user['password'])) {
    echo "❌ SECURITY FAILURE: Password hash is invalid!\n";
    exit(1);
}

$signInSuccess = UserAuth::signIn(['username' => 'testuserhash', 'password' => 'secure_password_123']);
if ($signInSuccess !== 'User Found') {
    echo "❌ signIn failed for correct password\n";
    exit(1);
}

$signInFailure = UserAuth::signIn(['username' => 'testuserhash', 'password' => 'wrong_password']);
if ($signInFailure !== 'User Not Found') {
    echo "❌ signIn succeeded for WRONG password\n";
    exit(1);
}

echo "✅ Password hashing security test passed!\n";

// Cleanup
unlink('test_auth.sqlite');
if ($originalEnv) {
    file_put_contents('.env', $originalEnv);
} else {
    unlink('.env');
}
