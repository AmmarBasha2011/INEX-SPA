# @section and @endSection

The `@section` directive is used to define a section of content that can be extended in a layout. The `@endSection` directive marks the end of the section.

#### Syntax

```php
@section('section_name')
    <!-- Section Content -->
@endSection
```

#### Example:

```php
@section('title')
    My Website Title
@endSection
```

This defines a section named `title` that can be rendered in a layout file.
