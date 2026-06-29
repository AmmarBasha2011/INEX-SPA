# Sentinel's Journal - INEX SPA

## 2025-05-14 - Initial Scan
Starting security assessment of INEX SPA.

## 2026-06-29 - AhmedTemplate XSS Vulnerability
**Vulnerability:** Multiple template directives (`@var`, `@postData`, `@getData`, `@getSession`, `@getCookie`) were echoing raw, unsanitized user-controllable data, leading to Reflected and Stored XSS.
**Learning:** While the standard `{{ $var }}` syntax used `htmlentities()`, specialized directives were implemented with direct echo, creating an inconsistent security model where developers might unknowingly introduce vulnerabilities by using these helpers.
**Prevention:** Always ensure that all template directives that output data to the browser apply appropriate encoding by default. Provide a "raw" alternative only if explicitly needed and clearly documented as dangerous.
