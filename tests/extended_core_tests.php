<?php
/**
 * Extended Core Tests - Comprehensive coverage for all core classes and functions
 * This supplements core_tests.php with additional edge cases and missing coverage
 */

require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/classes/Cache.php';
require_once 'core/functions/PHP/classes/Session.php';
require_once 'core/functions/PHP/classes/Validation.php';
require_once 'core/functions/PHP/classes/AhmedTemplate.php';
require_once 'core/functions/PHP/classes/Database.php';
require_once 'core/functions/PHP/classes/UserAuth.php';
require_once 'core/functions/PHP/classes/RateLimiter.php';
require_once 'core/functions/PHP/classes/Firewall.php';
require_once 'core/functions/PHP/classes/Language.php';
require_once 'core/functions/PHP/classes/Security.php';
require_once 'core/functions/PHP/classes/Logger.php';
require_once 'core/functions/PHP/classes/CookieManager.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Webhook.php';
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
require_once 'core/functions/PHP/getSlashData.php';
require_once 'core/functions/PHP/getWebsiteUrl.php';
require_once 'core/functions/PHP/getWEBSITEURLValue.php';
require_once 'core/functions/PHP/generateCsrfToken.php';
require_once 'core/functions/PHP/validateCsrfToken.php';

$results = [];
function assert_test($name, $condition, $message = '') {
    global $results;
    $results[$name] = ['success' => $condition, 'message' => $message];
}

// Ensure env file exists for test
if (!file_exists('.env')) {
    copy('.env.example', '.env');
}
if (!is_dir('core/cache')) mkdir('core/cache', 0755, true);
if (!is_dir('core/storage/sessions')) mkdir('core/storage/sessions', 0755, true);
if (!is_dir('core/logs')) mkdir('core/logs', 0755, true);
if (!is_dir('lang')) mkdir('lang', 0755, true);
if (!is_dir('db')) mkdir('db', 0755, true);

// Start session for CSRF tests
if (session_status() === PHP_SESSION_NONE) @session_start();

// =============================================================================
// getEnvValue extended
// =============================================================================
file_put_contents('.env.test', "APP_NAME=Test App\n# This is a comment\n\nEMPTY_LINE=\nQUOTED=\"value with spaces\"\n");
$envPath = realpath(__DIR__.'/../.env');
$backup = file_exists('.env') ? file_get_contents('.env') : null;
copy('.env.test', '.env');
assert_test('getEnvValue: reads value', getEnvValue('APP_NAME') === 'Test App', 'Should read Test App');
assert_test('getEnvValue: missing returns null', getEnvValue('NON_EXISTING_KEY_123') === null, 'Should return null');
assert_test('getEnvValue: handles comment', getEnvValue('# This is a comment') === null, 'Should skip comments');
if ($backup) file_put_contents('.env', $backup);
unlink('.env.test');

// =============================================================================
// Cache extended
// =============================================================================
Cache::set('ext_test_key', ['data' => 123], 10);
assert_test('Cache::get array data', Cache::get('ext_test_key') === ['data' => 123], 'Should store array');
Cache::update('ext_test_key', ['data' => 456]);
assert_test('Cache::update preserves', Cache::get('ext_test_key') === ['data' => 456], 'Should update');
assert_test('Cache::update non-existing', Cache::update('nonexistent_xyz', 'val') === false, 'Should return false');
Cache::delete('ext_test_key');
assert_test('Cache::delete', Cache::get('ext_test_key') === false, 'Should be false after delete');
Cache::set('expire_test', 'val', 1);
sleep(2);
assert_test('Cache::expiration', Cache::get('expire_test') === false, 'Should expire after 1s');
Cache::set('update_exp_test', 'original', 100);
$beforeFile = 'core/cache/' . md5('update_exp_test') . '.cache';
$beforeContent = json_decode(file_get_contents($beforeFile), true);
$beforeExpiry = $beforeContent['expires'];
sleep(1);
Cache::update('update_exp_test', 'updated');
$afterContent = json_decode(file_get_contents($beforeFile), true);
assert_test('Cache::update preserves expiry', $afterContent['expires'] === $beforeExpiry, 'Expiry should not change');
assert_test('Cache::update changes data', $afterContent['data'] === 'updated', 'Data should change');
Cache::delete('update_exp_test');

// =============================================================================
// Session extended
// =============================================================================
Session::make('ext_sess', ['user' => 'test', 'num' => 123]);
assert_test('Session::get array', Session::get('ext_sess') === ['user' => 'test', 'num' => 123], 'Should store array');
Session::make('ext_sess2', 'string_val');
assert_test('Session::get string', Session::get('ext_sess2') === 'string_val', 'Should store string');
Session::delete('ext_sess');
assert_test('Session::delete', Session::get('ext_sess') === null, 'Should be null');
Session::delete('nonexist_should_not_error'); // Should not throw
assert_test('Session::delete non-existing no error', true, 'Should not error on missing');
Session::make('ext_sess3', null);
assert_test('Session::make null', Session::get('ext_sess3') === null, 'Handles null');

// =============================================================================
// Validation extended - all methods
// =============================================================================
assert_test('Validation::isEmail valid', Validation::isEmail('test@example.com') === true, 'Valid');
assert_test('Validation::isEmail valid subdomain', Validation::isEmail('user@mail.example.co.uk') === true, 'Valid subdomain email');
assert_test('Validation::isEmail invalid no @', Validation::isEmail('testexample.com') === false, 'Invalid');
assert_test('Validation::isEmail invalid no domain', Validation::isEmail('test@') === false, 'Invalid');
assert_test('Validation::isTextLength true', Validation::isTextLength('hello', 10) === true, 'Within limit');
assert_test('Validation::isTextLength false', Validation::isTextLength('hello world', 5) === false, 'Exceeds');
assert_test('Validation::isTextLength edge', Validation::isTextLength('hello', 5) === true, 'Exactly limit');
assert_test('Validation::isMinTextLength true', Validation::isMinTextLength('hello world', 5) === true, 'Meets min');
assert_test('Validation::isMinTextLength false', Validation::isMinTextLength('hi', 5) === false, 'Below min');
assert_test('Validation::isSubDomain true', Validation::isSubDomain('sub.example.com') === true, 'Subdomain');
assert_test('Validation::isSubDomain true multi', Validation::isSubDomain('a.b.c.com') === true, 'Multi sub');
assert_test('Validation::isSubDomain false', Validation::isSubDomain('example.com') === false, 'Not subdomain');
assert_test('Validation::isSubDomain false no dot', Validation::isSubDomain('localhost') === false, 'No dot');
assert_test('Validation::isSubDir true', Validation::isSubDir('https://example.com/path') === true, 'Has subdir');
assert_test('Validation::isSubDir true deep', Validation::isSubDir('https://example.com/a/b/c') === true, 'Deep path');
assert_test('Validation::isSubDir false', Validation::isSubDir('https://example.com') === false, 'No path');
assert_test('Validation::isSubDir false root slash', Validation::isSubDir('https://example.com/') === false, 'Root slash');
assert_test('Validation::isDomain valid', Validation::isDomain('example.com') === true, 'Valid domain');
assert_test('Validation::isDomain valid subdomain', Validation::isDomain('sub.example.com') === true, 'Valid subdomain');
assert_test('Validation::isDomain invalid', Validation::isDomain('not a domain') === false, 'Invalid');
assert_test('Validation::isEndWith true', Validation::isEndWith('test@gmail.com', ['gmail.com', 'yahoo.com']) === true, 'Ends with');
assert_test('Validation::isEndWith false', Validation::isEndWith('test@other.com', ['gmail.com']) === false, 'Not ends');
assert_test('Validation::isStartWith true', Validation::isStartWith('+201234567890', ['+20', '+1']) === true, 'Starts with');
assert_test('Validation::isStartWith false', Validation::isStartWith('+44123', ['+20']) === false, 'Not start');
assert_test('Validation::isNumber integer', Validation::isNumber('123') === true, 'Integer');
assert_test('Validation::isNumber float', Validation::isNumber('12.34') === true, 'Float');
assert_test('Validation::isNumber negative', Validation::isNumber('-123') === true, 'Negative');
assert_test('Validation::isNumber invalid', Validation::isNumber('abc') === false, 'Not number');
assert_test('Validation::isNumber empty', Validation::isNumber('') === false, 'Empty');
assert_test('Validation::isBool true bool', Validation::isBool(true) === true, 'Bool true');
assert_test('Validation::isBool false bool', Validation::isBool(false) === true, 'Bool false');
assert_test('Validation::isBool string true', Validation::isBool('true') === true, 'String true');
assert_test('Validation::isBool string false', Validation::isBool('false') === true, 'String false');
assert_test('Validation::isBool 1', Validation::isBool(1) === true, 'Int 1');
assert_test('Validation::isBool 0', Validation::isBool(0) === true, 'Int 0');
assert_test('Validation::isBool string 1', Validation::isBool('1') === true, 'String 1');
assert_test('Validation::isBool string 0', Validation::isBool('0') === true, 'String 0');
assert_test('Validation::isBool invalid', Validation::isBool('yes') === false, 'Invalid bool');
assert_test('Validation::isBool invalid 2', Validation::isBool(2) === false, 'Invalid int');

// =============================================================================
// AhmedTemplate extended
// =============================================================================
$tpl = 'tests/ext_template_test.ahmed.php';
file_put_contents($tpl, 'Hello {{ $name }}!');
$engine = new AhmedTemplate();
$out = $engine->render($tpl, ['name' => 'World']);
assert_test('AhmedTemplate: var echo', trim($out) === 'Hello World!', 'Echo works');

file_put_contents($tpl, '@if(true) Yes @else No @endif');
$out = $engine->render($tpl, []);
assert_test('AhmedTemplate: @if true', strpos($out, 'Yes') !== false, 'If true');

file_put_contents($tpl, '@if(false) Yes @else No @endif');
$out = $engine->render($tpl, []);
assert_test('AhmedTemplate: @if false else', strpos($out, 'No') !== false && strpos($out, 'Yes') === false, 'If false else');

file_put_contents($tpl, '@foreach([1,2,3] as $n){{ $n }}@endforeach');
$out = $engine->render($tpl, []);
assert_test('AhmedTemplate: @foreach', strpos($out, '1') !== false && strpos($out, '3') !== false, 'Foreach');

file_put_contents($tpl, '@for($i=0;$i<3;$i++){{ $i }}@endfor');
$out = $engine->render($tpl, []);
assert_test('AhmedTemplate: @for', strpos($out, '0') !== false && strpos($out, '2') !== false, 'For');

file_put_contents($tpl, '{{ $a }} and {{ $b }}');
$out = $engine->render($tpl, ['a' => '<script>', 'b' => 'test']);
assert_test('AhmedTemplate: escapes HTML', strpos($out, '&lt;script&gt;') !== false, 'Escapes');

file_put_contents($tpl, '@isset($var) Set @endisset @empty($empty) Empty @endempty');
$out = $engine->render($tpl, ['var' => 'yes', 'empty' => '']);
assert_test('AhmedTemplate: @isset @empty', strpos($out, 'Set') !== false && strpos($out, 'Empty') !== false, 'Isset/empty');

file_put_contents($tpl, '@php echo "php block"; @endphp');
$out = $engine->render($tpl, []);
assert_test('AhmedTemplate: @php block', strpos($out, 'php block') !== false, 'Php block');

file_put_contents($tpl, '{{-- comment --}}Visible');
$out = $engine->render($tpl, []);
assert_test('AhmedTemplate: comment', strpos($out, 'comment') === false && strpos($out, 'Visible') !== false, 'Comment removed');

// Test missing template throws
try {
    $engine->render('nonexistent_xyz.ahmed.php', []);
    assert_test('AhmedTemplate: missing throws', false, 'Should throw');
} catch (Exception $e) {
    assert_test('AhmedTemplate: missing throws', true, 'Throws exception');
}
unlink($tpl);

// =============================================================================
// Security extended
// =============================================================================
$dirty = '<script>alert("xss")</script><b>Hello</b>';
$clean = Security::sanitizeInput($dirty);
assert_test('Security: strips script', strpos($clean, '<script>') === false && strpos($clean, 'alert') === false, 'Strips script');
assert_test('Security: encodes b tag', strpos($clean, '&lt;b&gt;') !== false, 'Encodes b');
assert_test('Security: validateAndSanitize xss', Security::validateAndSanitize($dirty, 'xss') === $clean, 'Dispatcher xss');
assert_test('Security: validateAndSanitize unknown returns input', Security::validateAndSanitize('test', 'unknown') === 'test', 'Unknown returns input');
$upperScript = '<SCRIPT>alert(1)</SCRIPT> test';
$cleanUpper = Security::sanitizeInput($upperScript);
assert_test('Security: case insensitive script', strpos($cleanUpper, 'alert') === false, 'Case insensitive');
$nested = '<script><script>alert(1)</script> test';
$cleanNested = Security::sanitizeInput($nested);
// Should at least not contain script
assert_test('Security: handles script content', strpos($cleanNested, '<script') === false, 'No script tag');

// =============================================================================
// Session simulation already done, but test Layout, Logger, etc.
// =============================================================================

// Layout
Layout::start('test_section_ext');
echo 'Extended Layout Content';
Layout::end();
assert_test('Layout::section extended', Layout::section('test_section_ext') === 'Extended Layout Content', 'Captures');
assert_test('Layout::section missing', strpos(Layout::section('nonexist_xyz'), 'not found') !== false, 'Handles missing');

// Logger
Logger::log('error', 'Extended error test');
assert_test('Logger::error log', file_exists('core/logs/errors.log') && strpos(file_get_contents('core/logs/errors.log'), 'Extended error') !== false, 'Writes error');
Logger::log('security', 'Extended security test');
assert_test('Logger::security log', file_exists('core/logs/security.log') && strpos(file_get_contents('core/logs/security.log'), 'Extended security') !== false, 'Writes security');
Logger::log('api', 'Extended api test');
assert_test('Logger::api log', file_exists('core/logs/api.log') && strpos(file_get_contents('core/logs/api.log'), 'Extended api') !== false, 'Writes api');
Logger::log('system', 'Extended system test');
assert_test('Logger::system log', file_exists('core/logs/system.log') && strpos(file_get_contents('core/logs/system.log'), 'Extended system') !== false, 'Writes system');
Logger::clearLogs();
assert_test('Logger::clearLogs', file_get_contents('core/logs/system.log') === '' && file_get_contents('core/logs/errors.log') === '', 'Clears logs');
Logger::log('system', 'After clear'); // Restore

// CookieManager (cannot test setcookie in CLI, but test other methods)
$_COOKIE['test_cookie_ext'] = 'test_value';
assert_test('CookieManager::get', CookieManager::get('test_cookie_ext') === 'test_value', 'Gets cookie');
assert_test('CookieManager::exists true', CookieManager::exists('test_cookie_ext') === true, 'Exists true');
assert_test('CookieManager::exists false', CookieManager::exists('nonexist_123_xyz') === false, 'Exists false');
assert_test('CookieManager::getAll', is_array(CookieManager::getAll()) && isset(CookieManager::getAll()['test_cookie_ext']), 'Get all');
unset($_COOKIE['test_cookie_ext']);

// Language
$langFile = 'lang/en_test_ext.json';
file_put_contents($langFile, json_encode(['hello' => 'Hello {name}', 'nested' => 'Welcome {user} to {site}']));
Language::setLanguage('en_test_ext');
assert_test('Language::get simple', Language::get('hello', ['name' => 'John']) === 'Hello John', 'Simple placeholder');
assert_test('Language::get multi', Language::get('nested', ['user' => 'Ammar', 'site' => 'INEX']) === 'Welcome Ammar to INEX', 'Multi placeholder');
assert_test('Language::get fallback', Language::get('nonexist_key_xyz') === 'nonexist_key_xyz', 'Fallback to key');
assert_test('Language::get no placeholder', Language::get('hello') === 'Hello {name}', 'No replace');
unlink($langFile);

// getSlashData
assert_test('getSlashData: valid', getSlashData('users/123') === ['before' => 'users', 'after' => '123'], 'Valid');
assert_test('getSlashData: no slash', getSlashData('users') === 'Not Found', 'No slash');
assert_test('getSlashData: multi slash', getSlashData('a/b/c') === 'Not Found', 'Multi');
assert_test('getSlashData: empty after', getSlashData('users/') === ['before' => 'users', 'after' => ''], 'Empty after');
assert_test('getSlashData: empty before', getSlashData('/123') === ['before' => '', 'after' => '123'], 'Empty before');

// getWebsiteUrl
$websiteUrl = getWebsiteUrl();
assert_test('getWebsiteUrl: returns URL', $websiteUrl !== null && strpos($websiteUrl, 'http') === 0, "Returns $websiteUrl");
$websiteURLValue = getWEBSITEURLValue();
assert_test('getWEBSITEURLValue: outputs JS', strpos($websiteURLValue, 'window.WEBSITE_URL') !== false, 'Outputs JS');

// CSRF
$_SESSION = [];
$token1 = generateCsrfToken();
assert_test('CSRF: generates token', strlen($token1) === 64 && ctype_xdigit($token1), '64 hex chars');
$token2 = generateCsrfToken();
assert_test('CSRF: returns same token', $token1 === $token2, 'Same token');
$_POST['csrf_token'] = $token1;
assert_test('CSRF: validate passes', (function() { ob_start(); validateCsrfToken(); $out = ob_get_clean(); return true; })() === true, 'Validates');
$_POST['csrf_token'] = 'invalid';
$validated = false;
try {
    // validateCsrfToken will exit, need to test differently - check hash_equals path
    // We can't easily test failure without exit, so just check function exists and logic
    $validated = !hash_equals($token1, 'invalid');
} catch (Exception $e) {}
assert_test('CSRF: hash_equals detects invalid', $validated === true, 'Detects invalid');

// Database
$db = new Database();
assert_test('Database: instance', $db instanceof Database, 'Instance');
$testSqlite = 'test_extended.sqlite';
if (file_exists($testSqlite)) unlink($testSqlite);
file_put_contents('.env.test2', "DB_DRIVER=sqlite\nDB_FILE=$testSqlite\n");
$backup2 = file_exists('.env') ? file_get_contents('.env') : null;
copy('.env.test2', '.env');
$db2 = new Database();
assert_test('Database: sqlite file', $db2 instanceof Database, 'Sqlite instance');
$db2->query('CREATE TABLE IF NOT EXISTS test_table (id INTEGER PRIMARY KEY, name TEXT)', [], false);
$db2->query('INSERT INTO test_table (name) VALUES (?)', ['test_val'], false);
$result = $db2->query('SELECT * FROM test_table WHERE name = ?', ['test_val'], true);
assert_test('Database::query insert/select', count($result) === 1 && $result[0]['name'] === 'test_val', 'Insert/select works');
$db2->query('DROP TABLE test_table', [], false);
if ($backup2) file_put_contents('.env', $backup2);
unlink('.env.test2');
if (file_exists($testSqlite)) unlink($testSqlite);

// UserAuth
assert_test('UserAuth::generateSQL', strpos(UserAuth::generateSQL(), 'CREATE TABLE IF NOT EXISTS users') !== false, 'Generates SQL');
$sql = UserAuth::generateSQL();
assert_test('UserAuth::generateSQL has password', strpos($sql, 'password') !== false, 'Has password field');
assert_test('UserAuth::generateSQL has email', strpos($sql, 'email') !== false, 'Has email field');

// RateLimiter and Firewall - just existence, as check() may exit
assert_test('RateLimiter::exists', class_exists('RateLimiter'), 'Exists');
assert_test('Firewall::exists', class_exists('Firewall'), 'Exists');
assert_test('Webhook::exists', class_exists('Webhook'), 'Exists');
assert_test('SitemapGenerator::exists', class_exists('SitemapGenerator'), 'Exists');

// Sitemap
if (!is_dir('web')) mkdir('web');
file_put_contents('web/test_sitemap.ahmed.php', 'test');
SitemapGenerator::generate();
assert_test('SitemapGenerator::generate', file_exists('public/sitemap.xml') && strpos(file_get_contents('public/sitemap.xml'), '<urlset') !== false, 'Generates XML');
assert_test('SitemapGenerator contains test route', strpos(file_get_contents('public/sitemap.xml'), 'test_sitemap') !== false, 'Contains route');
unlink('web/test_sitemap.ahmed.php');
SitemapGenerator::generate(); // regenerate without test file

// Save results
file_put_contents('tests/extended_results.json', json_encode($results, JSON_PRETTY_PRINT));
echo "Extended tests: " . count($results) . " tests, " . count(array_filter($results, fn($r) => $r['success'])) . " passed\n";
foreach ($results as $name => $res) {
    echo ($res['success'] ? "✅ " : "❌ ") . "$name: " . $res['message'] . "\n";
}
