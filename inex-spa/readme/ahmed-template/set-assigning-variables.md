# @set - Assigning Variables

The `@set` directive in Ahmed PHP Template Engine allows you to assign values to variables within a template. This is useful for defining dynamic content inside your templates without modifying the controller logic.

### Syntax

```php
@set(variable, value)
```

* `variable` is the name of the variable you want to assign.
* `value` is the value to be assigned to the variable.

### Example

```php
@set(title, 'Welcome to My Website')
<h1>{{ title }}</h1>
```

### Output

```html
<h1>Welcome to My Website</h1>
```

### Notes

* The variable assigned using `@set` can be accessed anywhere in the template using `{{ variable }}`.
* Variables set using `@set` persist only within the template scope where they are defined.
* You can use `@set` to store values for loops, conditions, or other dynamic content.
