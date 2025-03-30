# @dd - Debugging and Stopping Execution

The `@dd` directive in Ahmed PHP Template Engine is similar to `@dump`, but it immediately stops the execution of the script after printing the debug information. This is useful when you need to inspect a variable and prevent further rendering of the template.

### Syntax

```php
@dd(variable)
```

* `variable` is the variable you want to inspect.
* It prints the value and type of the variable, then stops execution.

### Example

```php
@set(user, ['name' => 'Ammar', 'age' => 13])
@dd(user)
<p>This line will not be executed.</p>
```

### Output

```html
array(
    "name" => "Ammar",
    "age" => 13
)
```

(The script stops here, so the paragraph is never rendered.)

### Notes

* `@dd` is useful for debugging when you need to halt execution after inspecting a variable.
* Should be removed in production templates to avoid breaking the page.
* Works similarly to Laravel’s `dd()` function by dumping the variable and stopping execution.
