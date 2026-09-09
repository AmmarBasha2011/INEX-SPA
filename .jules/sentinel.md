# Sentinel's Journal - INEX SPA

## 2025-05-14 - Initial Scan
Starting security assessment of INEX SPA.

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
