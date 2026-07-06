## 2025-05-14 - XSS Vulnerability in AhmedTemplate Directives
**Vulnerability:** Several AhmedTemplate directives (@var, @getData, @postData, @getSession, @getCookie) were echoing raw data without HTML escaping, leading to XSS.
**Learning:** While the standard `{{ }}` syntax was escaped, specialized directives were overlooked.
**Prevention:** Always use `htmlspecialchars` (or equivalent) for any directive that outputs user-controlled data. Added defensive comments to the template engine source.

## 2025-05-14 - Logic Bug in Security::sanitizeInput
**Vulnerability:** `Security::sanitizeInput` called `htmlspecialchars` before `preg_replace` to strip `<script>` tags, causing the regex to miss encoded tags.
**Learning:** Order of operations is critical in sanitization.
**Prevention:** Strip tags from raw input *before* encoding characters.
