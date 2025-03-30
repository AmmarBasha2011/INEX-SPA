# Cache Management Directives

Ahmed PHP Template Engine provides multiple directives to manage caching: `@setCache`, `@getCache`, `@updateCache`, and `@deleteCache`.

### @setCache - Store Data in Cache

#### Syntax

```php
@setCache(key, value, minutes)
```

* `key`: The cache identifier.
* `value`: The data to store.
* `minutes`: (Optional) Expiration time in minutes.

#### Example

```php
@setCache("user_profile", {"name": "Ahmed", "email": "ahmed@example.com"}, 60)
```

### @getCache - Retrieve Cached Data

#### Syntax

```php
@getCache(key)
```

* `key`: The cache identifier.

#### Example

```php
@var(user, @getCache("user_profile"))
<p>Name: @user["name"]</p>
<p>Email: @user["email"]</p>
```

### @updateCache - Modify Cached Data

#### Syntax

```php
@updateCache(key, value)
```

* `key`: The cache identifier.
* `value`: The new data to store.

#### Example

```php
@updateCache("user_profile", {"name": "Ahmed Updated", "email": "new@example.com"})
```

### @deleteCache - Remove Cached Data

#### Syntax

```php
@deleteCache(key)
```

* `key`: The cache identifier.

#### Example

```php
@deleteCache("user_profile")
```

### Notes

* `@setCache` stores data for a specified duration (default is unlimited if `minutes` is omitted).
* `@getCache` retrieves cached values.
* `@updateCache` modifies existing cache entries.
* `@deleteCache` removes specific cache entries.
