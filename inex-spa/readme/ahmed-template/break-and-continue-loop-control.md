# @break and @continue - Loop Control

The `@break` and `@continue` directives in Ahmed PHP Template Engine allow you to control the flow of loops by either exiting the loop entirely (`@break`) or skipping the current iteration (`@continue`).

### Syntax

```php
@break
@continue
```

* `@break` exits the loop immediately.
* `@continue` skips the current iteration and moves to the next one.

### Example

```php
@foreach(numbers as number)
    @if(number == 3)
        @break
    @endif
    <p>{{ number }}</p>
@endforeach
```

### Output

```html
<p>1</p>
<p>2</p>
```

(The loop stops when `number == 3`.)

#### Using `@continue`

```php
@foreach(numbers as number)
    @if(number == 2)
        @continue
    @endif
    <p>{{ number }}</p>
@endforeach
```

### Output

```html
<p>1</p>
<p>3</p>
```

(The number `2` is skipped.)

### Notes

* `@break` is useful when you need to stop a loop early based on a condition.
* `@continue` is helpful when you want to skip certain iterations without stopping the loop.
* Both directives work inside `@for`, `@foreach`, and `@while` loops.
