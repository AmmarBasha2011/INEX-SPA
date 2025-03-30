# @do @whileCond - Do-While Loop

The `@do` and `@whileCond` directives in Ahmed PHP Template Engine allow you to create a do-while loop, which executes the block of code at least once before checking the condition.

### Syntax

```php
@do
    // Code to execute
@whileCond(condition)
```

* `condition`: The expression that determines whether the loop continues.

### Description

* The block inside `@do` runs first, regardless of the condition.
* After execution, `@whileCond` checks the condition; if true, the loop repeats.
* Useful for cases where you need to ensure the loop executes at least once.

### Example

```php
@define(counter, 1)

@do
    <p>Iteration: @counter</p>
    @define(counter, counter + 1)
@whileCond(counter <= 3)
```

### Expected Output

```html
<p>Iteration: 1</p>
<p>Iteration: 2</p>
<p>Iteration: 3</p>
```

### Notes

* The loop guarantees one execution before checking the condition.
* Ensure the condition changes within the loop to prevent infinite loops.
* Similar to `@while`, but always executes at least once before checking.
