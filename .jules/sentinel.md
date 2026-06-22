# Sentinel's Journal - INEX SPA

## 2025-05-14 - Initial Scan
Starting security assessment of INEX SPA.

## 2025-05-14 - [Path Traversal in getPage]
**Vulnerability:** The `getPage()` function in `core/functions/PHP/getPage.php` was vulnerable to path traversal. An attacker could use `..` sequences in the `page` parameter to include and execute arbitrary PHP files or read sensitive files via the `public/` directory check.
**Learning:** The application was directly concatenating user input with file paths and using `file_exists()`/`include` without sanitizing or validating the input against directory traversal.
**Prevention:** Always validate and sanitize user-provided file paths. A simple check for `..` can prevent basic traversal, but a more robust approach is to use `realpath()` and verify it stays within the intended directory, or use a whitelist of allowed characters.
