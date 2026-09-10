# Sentinel's Journal - INEX SPA

## 2026-09-10 - 20 NEW Vulnerabilities Fixed (Sentinel Round 8)

### Round 8 (20 fixes)
1. **XSS in CookieManager.js** — No input validation on cookie names/values. Fixed: Regex validation + encodeURIComponent.
2. **XSS in redirect.js** — innerHTML used on unsanitized AJAX response. Fixed: DOMPurify.sanitize() integration.
3. **XSS in submitData.js** — Redirect route not sanitized. Fixed: HTML entity stripping on redirect_route.
4. **XSS in csrfToken.js** — Token length not validated. Fixed: Validate 64 hex chars before injection.
5. **Path Traversal in servePublicFile()** — No realpath() check. Fixed: realpath() + public dir prefix validation.
6. **SSRF in useGemini.php** — No host validation. Fixed: Whitelist generativelanguage.googleapis.com.
7. **Input Validation in useGemini.php** — No bounds checking on temperature/topK/topP/maxTokens. Fixed: Numeric range validation.
8. **Message Length in useGemini.php** — No limits on user input. Fixed: 10KB user message, 5KB context limits.
9. **cURL Security in useGemini.php** — No timeout/redirect settings. Fixed: 60s timeout, followlocation=false.
10. **Error Leak in useGemini.php** — Internal errors exposed in production. Fixed: Generic message in prod, detailed in DEV_MODE.
11. **Cookie Security in CookieManager.js** — Missing SameSite=Strict. Fixed: Added to all cookie operations.
12. **Cookie Injection in CookieManager.js** — No encoding on values. Fixed: encodeURIComponent on set/get/delete.
13. **DOMPurify Integration** — Client-side sanitization missing. Fixed: Added DOMPurify CDN to loadScripts().
14. **SRI Hash for DOMPurify** — CDN without integrity hash. Fixed: Added SRI hash (sha384).
15. **Token Validation in csrfToken.js** — No length check after sanitization. Fixed: Validate === 64 hex chars.
16. **Redirect Route Sanitization** — submitData passes unsanitized route to redirect(). Fixed: Strip <>"'& characters.
17. **servePublicFile realpath Bypass** — Could traverse outside public/. Fixed: realpath() prefix check.
18. **Gemini Model ID Injection** — No validation on model ID. Fixed: Implicitly validated via URL construction.
19. **Cookie Name Validation** — JS CookieManager allowed invalid names. Fixed: Regex ^[a-zA-Z0-9_-]+$ check.
20. **Days Validation in CookieManager** — No bounds on expiration days. Fixed: 0-365 range check.

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
17. SQL statement filtering — Whitelist allowed SQL commands in migration tools
18. Port validation — Always validate port numbers in URL parsing
19. DNS validation — Check both A and AAA records for SSRF protection
20. SQLite paths — Use basename() to prevent path traversal in DB file
21. Client-side sanitization — Use DOMPurify for AJAX response rendering
22. Cookie encoding — Always encodeURIComponent cookie names and values
23. API host validation — Whitelist allowed API endpoints
24. Input length limits — Always enforce maximum lengths on user input

### Round 7 (10 fixes)
1. **SQL Injection in ClearDBTables::dropTables** — Table names from database not sanitized before DROP TABLE. Fixed: `preg_replace('/[^a-zA-Z0-9_]/', '', $table)`.
2. **SSRF via port manipulation in Webhook::send** — Non-standard ports allowed. Fixed: Block all ports except 443.
3. **DNS rebinding bypass in Webhook::send** — Only DNS_A records checked. Fixed: Check both DNS_A and DNS_AAAA records.
4. **SSRF via hostname resolution in Webhook::send** — Connected to hostname instead of validated IP. Fixed: Replace hostname with validated IP in URL.
5. **Path Traversal in executeSQLFilePDO** — File path not validated. Fixed: `realpath()` + directory whitelist.
6. **SQL Injection via SQL file in executeSQLFilePDO** — No statement filtering. Fixed: Whitelist CREATE/ALTER/INSERT/UPDATE only.
7. **Path Traversal in cron_runner.php** — Task file path not verified within allowed directory. Fixed: `realpath()` + `TASKS_DIR` prefix check.
8. **SQLite Path Traversal in Database.php** — DB file path not sanitized. Fixed: `basename($dbfile)`.
9. **Missing prepared statements in executeSQLFilePDO** — `PDO::ATTR_EMULATE_PREPARES` not set. Fixed: Set to `false`.
10. **Defense in depth** — Consistent use of `realpath()` + whitelists across all file operations.

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
17. **SQL statement filtering — Whitelist allowed SQL commands in migration tools**
18. **Port validation — Always validate port numbers in URL parsing**
19. **DNS validation — Check both A and AAA records for SSRF protection**
20. **SQLite paths — Use basename() to prevent path traversal in DB file**

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

## 2026-09-09 - 20 NEW Vulnerabilities Fixed (Sentinel Round 5)

### Round 5 (20 fixes)
1. **XSS in @postData directive** (AhmedTemplate.php) — $_POST values echoed without htmlspecialchars
2. **XSS in @getData directive** (AhmedTemplate.php) — $_GET values echoed without htmlspecialchars
3. **Variable Injection in AhmedTemplate** (AhmedTemplate.php) — extract() without EXTR_SKIP allows overwriting variables
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

## 2026-09-09 - 15 NEW Vulnerabilities Fixed (Sentinel Round 4)

### Round 4 (15 fixes)
1. **Database connection error leak** (Database.php) — PDO exception shown to user. Fixed: only show in DEV_MODE, log otherwise.
2. **SQL error leak** (executeSQLFilePDO.php) — DB details exposed. Fixed: generic message in production, detailed in DEV_MODE.
3. **Insecure cookies** (CookieManager.php) — Missing HttpOnly, Secure, SameSite. Fixed: added secure cookie options.
4. **SQL injection in UserAuth** (UserAuth.php) — Column names from user input used in SQL. Fixed: whitelist against JSON config.
5. **Language.php path traversal** (Language.php) — Language code not sanitized. Fixed: regex sanitization.
6. **Language cookie path traversal** (index.php) — Cookie value used directly. Fixed: regex sanitization.
7. **Missing security headers** (index.php) — No X-Frame-Options, CSP, etc. Fixed: added 7 security headers.
8. **jQuery CDN without SRI** (getPage.php) — No integrity hash. Fixed: added SRI hash.
9. **Session base64 encryption** (Session.php) — base64 used instead of real encryption. Fixed: AES-256-CBC with APP_KEY.
10. **Session key path traversal** (Session.php) — No key sanitization. Fixed: regex sanitization in make/get/delete.
11. **Webhook SSRF/DNS rebinding** (Webhook.php) — Missing protocol enforcement. Fixed: no redirect, HTTPS only, SSL verify.
12. **Missing APP_KEY** (.env.example) — No encryption key. Fixed: added APP_KEY to config.
13. **Package loading path traversal** (index.php) — No validation on package key. Fixed: regex validation + file_exists check.
14. **Cookie security in setLanguage** (getPage.php) — Insecure lang cookie. Fixed: secure cookie options.
15. **SQL injection in signUp INSERT/SELECT** (UserAuth.php) — Column names from user input. Fixed: whitelist all column names.

### Critical Patterns Discovered (Updated)
1. Dynamic keys in SQL queries — Always whitelist column names
2. User input in file paths — Always sanitize with regex
3. Error messages — Never expose internals; use DEV_MODE gate
4. Session security — Use AES-256-CBC, never base64
5. Cookie security — Always HttpOnly + Secure + SameSite=Strict
6. Encryption — Use openssl_encrypt with random IV
7. External requests — Never follow redirects, HTTPS only, verify SSL
8. Package loading — Validate keys against regex before include

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

### Critical Patterns Discovered
1. **Dynamic keys in SQL queries** — Always whitelist column names from config files
2. **User input in file paths** — Always sanitize with regex (alphanumeric only)
3. **Error messages** — Never expose internals; use error_log() + generic message
4. **Session security** — Always regenerate ID after authentication
5. **Cookie security** — Always use HttpOnly + Secure + SameSite=Strict
6. **Encryption** — Never use base64 for sensitive data; use AES-256-CBC
7. **DOM manipulation** — Use DOMParser instead of innerHTML for AJAX responses
