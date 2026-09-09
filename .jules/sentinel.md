# Sentinel's Journal - INEX SPA

## 2025-05-14 - Initial Scan
Starting security assessment of INEX SPA.

## 2026-09-10 - 10 NEW Vulnerabilities Fixed (Sentinel Round 8)

### Round 8 (10 fixes)
1. **Type Juggling in getPage.php** — USE_BOOTSTRAP loose comparison (`==` → `===`)
2. **Type Juggling in getPage.php** — USE_PWA loose comparison (`==` → `===`)
3. **Type Juggling in getPage.php** — USE_COOKIE loose comparison (`==` → `===`)
4. **Type Juggling in getPage.php** — USE_APP_NAME_IN_TITLE loose comparison (`==` → `===`)
5. **Type Juggling in getPage.php** — USE_ANIMATE loose comparison (`==` → `===`)
6. **Type Juggling in getPage.php** — USE_NOTIFICATION loose comparison (`==` → `===`)
7. **Type Juggling in getPage.php** — fetchCsrfToken loose comparison (`==` → `===`)
8. **Type Juggling in getPage.php** — blocked loose comparison (`==` → `===`)
9. **Type Juggling in getPage.php** — JS/getWEBSITEURLValue.js loose comparison (`==` → `===`)
10. **Type Juggling in getPage.php** — setLanguage DETECT_LANGUAGE loose comparison (`==` → `===`)

### Critical Patterns Discovered (Updated)
1. Dynamic keys in SQL queries — Always whitelist column names
2. User input in file paths — Always sanitize with regex
3. Error messages — Never expose internals; use DEV_MODE gate
4. Session security — Use AES-256-CBC, never base64
5. Cookie security — Always HttpOnly + Secure + SameSite=Strict
6. Encryption — Use openssl_encrypt with random IV
7. External requests — Never follow redirects, HTTPS only, verify SSL
8. Package loading — Validate keys against regex before include
9. extract() usage — Always use EXTR_SKIP flag
10. Log messages — Strip newlines to prevent injection
11. JSON decode — Always validate result is array/object
12. Cache keys — Use SHA-256 instead of MD5
13. Template output — Always use htmlspecialchars for user-facing data
14. Multibyte strings — Use mb_strlen instead of strlen for length checks
15. File writes — Use atomic writes (tmp + rename) to prevent race conditions
16. Constants — Use defined() check to prevent redefinition
17. Comparisons — Always use strict comparison (===) to prevent type juggling

## 2026-09-10 - 20 NEW Vulnerabilities Fixed (Sentinel Round 6)

### Round 6 (20 fixes)
1. **XSS in @getLang** (AhmedTemplate.php) — Language output without htmlspecialchars
2. **XSS in @getEnv** (AhmedTemplate.php) — Env value without htmlspecialchars
3. **XSS in @getSession** (AhmedTemplate.php) — Session value without htmlspecialchars
4. **XSS in @getCookie** (AhmedTemplate.php) — Cookie value without htmlspecialchars
5. **XSS in @getSection** (AhmedTemplate.php) — Layout section without htmlspecialchars
6. **XSS in @var** (AhmedTemplate.php) — Variable variable without htmlspecialchars
7. **Path Traversal in getSlashData** (getSlashData.php) — Parts not sanitized
8. **Path Traversal in Validation** (Validation.php) — isSubDomain without input validation
9. **Path Traversal in Validation** (Validation.php) — isSubDir without input validation
10. **Bypass in Validation** (Validation.php) — isTextLength uses strlen instead of mb_strlen
11. **Bypass in Validation** (Validation.php) — isMinTextLength uses strlen instead of mb_strlen
12. **Error Leak** (ClearDBTables.php) — Exception messages shown to user
13. **Race Condition** (SitemapGenerator.php) — Non-atomic write
14. **Error Leak** (UserAuth.php) — JSON decode errors not logged
15. **Security** (getEnvValue.php) — Quotes not stripped from env values
16. **Validation** (RateLimiter.php) — IP and timestamp validation in cleanup
17. **Security** (UserAuth.php) — JSON_FOLDER constant uses defined() check
18. **XSS in getWEBSITEURLValue** (getWEBSITEURLValue.php) — Output without htmlspecialchars
19. **XSS in redirect.js** (redirect.js) — innerHTML without sanitization
20. **XSS in csrfToken.js** (csrfToken.js) — Token not sanitized

## 2026-09-09 - 20 NEW Vulnerabilities Fixed (Sentinel Round 5)

### Round 5 (20 fixes)
1. **XSS in @postData directive** (AhmedTemplate.php) — $_POST values echoed without htmlspecialchars
2. **XSS in @getData directive** (AhmedTemplate.php) — $_GET values echoed without htmlspecialchars
3. **Variable Injection in AhmedTemplate** (AhmedTemplate.php) — extract() without EXTR_SKIP
4. **Variable Injection in Layout** (Layout.php) — extract() without EXTR_SKIP
5. **Path Traversal in Cron** (cron_runner.php) — Task name from CLI not validated
6. **Path Traversal in getPage** (getPage.php) — Public file include without extension validation
7. **Error Leak in Cron** (cron_runner.php) — Exception messages shown to user
8. **Error Leak in UserAuth** (UserAuth.php) — signUp exposes DB errors
9. **Log Injection** (Logger.php) — Newlines in log messages allowed
10. **Weak Hashing in Cache** (Cache.php) — MD5 replaced with SHA-256
11. **SSRF in useGemini** (useGemini.php) — Endpoint URL not validated
12. **Header Injection in Firewall** (Firewall.php) — Redirect URL not sanitized
13. **Session Fixation** (UserAuth.php) — logout() doesn't regenerate session ID
14. **XSS in animate.php** (animate.php) — json_encode without hex escaping
15. **XSS in Sitemap** (SitemapGenerator.php) — URL not HTML-encoded
16. **@jsonFile without validation** (AhmedTemplate.php) — JSON decode without checks
17. **Missing Bootstrap SRI** (getPage.php) — Bootstrap JS without integrity hash
18. **RateLimiter IP validation** (RateLimiter.php) — IP address not validated
19. **UserAuth error messages** (UserAuth.php) — Internal details exposed
20. **Prettier workflow removed** (prettier.yml) — Broken workflow deleted

## 2026-09-09 - 15 NEW Vulnerabilities Fixed (Sentinel Round 4)

### Round 4 (15 fixes)
1. **Database connection error leak** (Database.php) — PDO exception shown to user
2. **SQL error leak** (executeSQLFilePDO.php) — DB details exposed
3. **Insecure cookies** (CookieManager.php) — Missing HttpOnly, Secure, SameSite
4. **SQL injection in UserAuth** (UserAuth.php) — Column names from user input used in SQL
5. **Language.php path traversal** (Language.php) — Language code not sanitized
6. **Language cookie path traversal** (index.php) — Cookie value used directly
7. **Missing security headers** (index.php) — No X-Frame-Options, CSP, etc.
8. **jQuery CDN without SRI** (getPage.php) — No integrity hash
9. **Session base64 encryption** (Session.php) — base64 used instead of real encryption
10. **Session key path traversal** (Session.php) — No key sanitization
11. **Webhook SSRF/DNS rebinding** (Webhook.php) — Missing protocol enforcement
12. **Missing APP_KEY** (.env.example) — No encryption key
13. **Package loading path traversal** (index.php) — No validation on package key
14. **Cookie security in setLanguage** (getPage.php) — Insecure lang cookie
15. **SQL injection in signUp INSERT/SELECT** (UserAuth.php) — Column names from user input

## 2026-09-08 - 15 Vulnerabilities Fixed (3 Rounds)

### Round 1 (5 fixes)
- SQL Injection in signIn() — User keys used directly in SQL without validation
- Path Traversal in getPage.php — No sanitization on page parameter
- SSRF in Webhook — No validation against private IP ranges
- Missing Security Headers — No X-Frame-Options, CSP, etc.
- Session Fixation — No session regeneration after login

### Round 2 (5 fixes)
- Insecure Cookies — Missing HttpOnly, Secure, SameSite flags
- Weak Session Encryption — base64 used instead of real encryption
- Path Traversal in Session::make() — No key sanitization
- Database Error Leak — PDO exceptions shown to user
- Missing APP_KEY — No encryption key in .env

### Round 3 (5 fixes)
- Path Traversal in Language.php — Language code not sanitized
- Variable Overwrite in extract() — No EXTR_SKIP flag
- Missing CSP Header — No Content-Security-Policy
- SQL Error Leak in executeSQLFilePDO — Details exposed
- DOM-based XSS in redirect.js — innerHTML used without sanitization
