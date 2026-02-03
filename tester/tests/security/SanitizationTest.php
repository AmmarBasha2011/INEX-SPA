<?php

// tester/tests/security/SanitizationTest.php

runTest('Security::sanitizeInput - basic string', function () {
    assertEquals('hello', Security::sanitizeInput('hello'));
});

runTest('Security::sanitizeInput - html entities', function () {
    assertEquals('&lt;b&gt;bold&lt;/b&gt;', Security::sanitizeInput('<b>bold</b>'));
});

runTest('Security::validateAndSanitize - xss', function () {
    $input = '<img src=x onerror=alert(1)>';
    $expected = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    assertEquals($expected, Security::validateAndSanitize($input, 'xss'));
});

$payloads = [
    '<script>alert(1)</script>',
    '<img src=x onerror=alert(1)>',
    '<svg onload=alert(1)>',
    'javascript:alert(1)',
    '"><script>alert(1)</script>',
    '<a href="javascript:alert(1)">XSS</a>',
    '<iframe src="javascript:alert(1)"></iframe>',
    '<body onload=alert(1)>',
    '<details open ontoggle=alert(1)>',
    '<video><source onerror=alert(1)>',
    '<audio src=x onerror=alert(1)>',
    '<object data="javascript:alert(1)"></object>',
    '<embed src="javascript:alert(1)"></embed>',
    '<math><mtext><option><annotation><script>alert(1)</script></annotation></option></mtext></math>',
    '<form><button formaction="javascript:alert(1)">XSS</button></form>',
    '<isindex type=image src=1 onerror=alert(1)>',
    '<marquee onstart=alert(1)>XSS</marquee>',
    '<input autofocus onfocus=alert(1)>',
];

foreach ($payloads as $i => $payload) {
    runTest("Sanitization XSS Payload Test $i", function () use ($payload, $i) {
        $sanitized = Security::sanitizeInput($payload);
        assertFalse(strpos($sanitized, '<script') !== false && strpos($sanitized, '</script>') !== false, "Script tag not removed for payload $i");
        assertTrue(strpos($sanitized, '<') === false || strpos($sanitized, '&lt;') !== false, "Angle bracket not escaped for payload $i");
    });
}

for ($i = 0; $i < 20; $i++) {
    runTest("Sanitization repetitive test $i", function () use ($i) {
        $input = "User input $i <script>alert($i)</script>";
        $sanitized = Security::sanitizeInput($input);
        assertTrue(strpos($sanitized, '<script') === false);
    });
}
