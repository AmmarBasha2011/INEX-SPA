# Sentinel's Journal - INEX SPA

## 2025-05-14 - Initial Scan
Starting security assessment of INEX SPA.

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
