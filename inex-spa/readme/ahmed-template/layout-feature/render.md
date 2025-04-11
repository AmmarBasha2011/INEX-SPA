# @render

The `@render` directive is used for generate page:

## Syntax

```php
@render("LayoutFile", "ContentFile", "requestType", "data")
```

## Example

### Step 1: Create layouts/main.ahmed.php

```php
<h1>Hi, $name</h1>
@getSection('content')
```

### Step 2: Create web/index.ahmed.php

```php
@section("content")
<p>You are user</p>
@endSection
```

### Step 3: Add @render at end of web/index.ahmed.php

```php
@render("main", "index", "GET", ["name" => "INEX SPA"])
```

Finally, You are used it!!!
