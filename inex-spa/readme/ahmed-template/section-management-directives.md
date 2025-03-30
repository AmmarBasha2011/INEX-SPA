# Section Management Directives

Ahmed PHP Template Engine provides the `@getSection` directive to retrieve content from named sections.

### @getSection - Retrieve Section Content

#### Syntax

```php
@getSection(name)
```

* `name`: The section identifier.

#### Example

```php
@section("header")
    <h1>Welcome to My Website</h1>
@endsection

@var(headerContent, @getSection("header"))
<div class="header">@headerContent</div>
```

### Notes

* `@getSection` is used to fetch and display content stored in `@section` blocks.
* Sections allow for better content organization and reuse.
