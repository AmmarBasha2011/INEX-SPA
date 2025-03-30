# @var - Define and Use Variables

The `@var` directive in Ahmed PHP Template Engine allows you to define and store variables within your template.

### Syntax

```php
@var(variableName, value)
```

* `variableName`: The name of the variable.
* `value`: The value to assign to the variable.

### Description

* This directive sets a variable to be used later in the template.
* Supports strings, numbers, and expressions.
* Variables can be accessed using `@variableName`.

### Example

```php
@var(name, "Ahmed")
@var(age, 25)

<p>Name: @name</p>
<p>Age: @age</p>
```

### Expected Output

```html
<p>Name: Ahmed</p>
<p>Age: 25</p>
```

### Notes

* Variables persist throughout the template unless redefined.
* Supports dynamic values and calculations (e.g., `@var(total, price * quantity)`).
