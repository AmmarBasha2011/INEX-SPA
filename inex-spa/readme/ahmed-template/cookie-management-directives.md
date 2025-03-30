# Cookie Management Directives

Ahmed PHP Template Engine provides multiple directives to manage browser cookies: `@setCookie`, `@getCookie`, `@existsCookie`, `@deleteCookie`, and `@getAllCookies`.

### @setCookie - Set a Cookie

#### Syntax

```php
@setCookie(name, value, minutes)
```

* `name`: The cookie name.
* `value`: The value to store.
* `minutes`: (Optional) Expiration time in minutes.

#### Example

```php
@setCookie("user", "Ahmed", 60)
```

### @getCookie - Retrieve a Cookie

#### Syntax

```php
@getCookie(name)
```

* `name`: The cookie name.

#### Example

```php
@var(username, @getCookie("user"))
<p>Welcome, @username</p>
```

### @existsCookie - Check If a Cookie Exists

#### Syntax

```php
@existsCookie(name)
```

* `name`: The cookie name.

#### Example

```php
@if(@existsCookie("user"))
    <p>User is logged in.</p>
@endif
```

### @deleteCookie - Delete a Cookie

#### Syntax

```php
@deleteCookie(name)
```

* `name`: The cookie name.

#### Example

```php
@deleteCookie("user")
```

### @getAllCookies - Retrieve All Cookies

#### Syntax

```php
@getAllCookies()
```

#### Example

```php
@var(cookies, @getAllCookies())
@foreach(cookies as key => value)
    <p>@key: @value</p>
@endforeach
```

### Notes

* Cookies are stored in the user's browser.
* `@setCookie` uses default expiration if `minutes` is not provided.
* `@deleteCookie` removes a specific cookie.
* `@getAllCookies` retrieves all stored cookies as an array.
