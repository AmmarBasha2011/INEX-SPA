<?php

// tester/tests/framework/ValidationTest.php

runTest('Validation::isEmail - valid email', function () {
    assertTrue(Validation::isEmail('test@example.com'));
});

runTest('Validation::isEmail - invalid email (no @)', function () {
    assertFalse(Validation::isEmail('testexample.com'));
});

runTest('Validation::isEmail - invalid email (no domain)', function () {
    assertFalse(Validation::isEmail('test@'));
});

runTest('Validation::isEmail - empty string', function () {
    assertFalse(Validation::isEmail(''));
});

// Loop for 30 tests
for ($i = 0; $i < 30; $i++) {
    $email = "user$i@domain$i.com";
    runTest("Validation::isEmail - loop test $i ($email)", function () use ($email) {
        assertTrue(Validation::isEmail($email));
    });
}

runTest('Validation::isTextLength - exact length', function () {
    assertTrue(Validation::isTextLength('hello', 5));
});

runTest('Validation::isTextLength - less than max', function () {
    assertTrue(Validation::isTextLength('hello', 10));
});

runTest('Validation::isTextLength - greater than max', function () {
    assertFalse(Validation::isTextLength('hello', 3));
});

for ($i = 1; $i <= 10; $i++) {
    $text = str_repeat('a', $i);
    runTest("Validation::isTextLength - loop test $i (length $i, max 10)", function () use ($text) {
        assertTrue(Validation::isTextLength($text, 10));
    });
}

runTest('Validation::isMinTextLength - exact length', function () {
    assertTrue(Validation::isMinTextLength('hello', 5));
});

runTest('Validation::isMinTextLength - greater than min', function () {
    assertTrue(Validation::isMinTextLength('hello', 3));
});

runTest('Validation::isMinTextLength - less than min', function () {
    assertFalse(Validation::isMinTextLength('hello', 10));
});

runTest('Validation::isSubDomain - single dot (not subdomain)', function () {
    assertFalse(Validation::isSubDomain('example.com'));
});

runTest('Validation::isSubDomain - two dots (subdomain)', function () {
    assertTrue(Validation::isSubDomain('sub.example.com'));
});

runTest('Validation::isSubDomain - three dots (nested subdomain)', function () {
    assertTrue(Validation::isSubDomain('a.b.example.com'));
});

runTest('Validation::isSubDir - root URL', function () {
    assertFalse(Validation::isSubDir('http://example.com'));
});

runTest('Validation::isSubDir - root URL with slash', function () {
    assertFalse(Validation::isSubDir('http://example.com/'));
});

runTest('Validation::isSubDir - URL with path', function () {
    assertTrue(Validation::isSubDir('http://example.com/path'));
});

runTest('Validation::isDomain - valid domain', function () {
    assertTrue(Validation::isDomain('example.com'));
});

runTest('Validation::isDomain - invalid domain', function () {
    assertFalse(Validation::isDomain('not a domain'));
});

runTest('Validation::isEndWith - positive match', function () {
    assertTrue(Validation::isEndWith('hello world', ['world', 'earth']));
});

runTest('Validation::isEndWith - negative match', function () {
    assertFalse(Validation::isEndWith('hello world', ['hello', 'earth']));
});

runTest('Validation::isStartWith - positive match', function () {
    assertTrue(Validation::isStartWith('hello world', ['hello', 'hi']));
});

runTest('Validation::isStartWith - negative match', function () {
    assertFalse(Validation::isStartWith('hello world', ['world', 'hi']));
});

runTest('Validation::isNumber - integer', function () {
    assertTrue(Validation::isNumber(123));
});

runTest('Validation::isNumber - numeric string', function () {
    assertTrue(Validation::isNumber('123.45'));
});

runTest('Validation::isNumber - non-numeric', function () {
    assertFalse(Validation::isNumber('abc'));
});

runTest('Validation::isBool - true (bool)', function () {
    assertTrue(Validation::isBool(true));
});

runTest('Validation::isBool - false (bool)', function () {
    assertTrue(Validation::isBool(false));
});

runTest('Validation::isBool - "true" (string)', function () {
    assertTrue(Validation::isBool('true'));
});

runTest('Validation::isBool - "false" (string)', function () {
    assertTrue(Validation::isBool('false'));
});

runTest('Validation::isBool - 1 (int)', function () {
    assertTrue(Validation::isBool(1));
});

runTest('Validation::isBool - 0 (int)', function () {
    assertTrue(Validation::isBool(0));
});

runTest('Validation::isBool - "1" (string)', function () {
    assertTrue(Validation::isBool('1'));
});

runTest('Validation::isBool - "0" (string)', function () {
    assertTrue(Validation::isBool('0'));
});

runTest('Validation::isBool - invalid bool', function () {
    assertFalse(Validation::isBool('yes'));
});

// More tests for isDomain
$domains = ['google.com', 'example.org', 'my-site.net', 'sub.domain.co.uk'];
foreach ($domains as $d) {
    runTest("Validation::isDomain - $d", function () use ($d) {
        assertTrue(Validation::isDomain($d));
    });
}
