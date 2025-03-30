# @while - Looping Until a Condition is Met

The `@while` directive in Ahmed PHP Template Engine allows you to execute a block of code repeatedly as long as a specified condition evaluates to `true`.

### Syntax

```
@while(condition)
    // Code to execute
@endwhile
```

### Example

```
@while($count <= 3)
    <p>Count: {{ $count }}</p>
    @php $count++ @endphp
@endwhile
```

### Output (if `$count = 1` initially)

```
<p>Count: 1</p>
<p>Count: 2</p>
<p>Count: 3</p>
```

### Notes

* The loop continues execution as long as `condition` is `true`.
* Ensure the condition eventually becomes `false` to avoid infinite loops.
* You can modify the loop variable inside the loop using `@php`.
