# @for - Looping with a Fixed Range

The `@for` directive in Ahmed PHP Template Engine allows you to loop through a specific range of numbers, similar to a traditional `for` loop in PHP.

### Syntax

```
@for(variable = start; condition; increment)
    // Code to execute
@endfor
```

### Example

```
@for($i = 1; $i <= 5; $i++)
    <p>Iteration: {{ $i }}</p>
@endfor
```

### Output

```
<p>Iteration: 1</p>
<p>Iteration: 2</p>
<p>Iteration: 3</p>
<p>Iteration: 4</p>
<p>Iteration: 5</p>
```

### Notes

* The loop will execute as long as the condition remains `true`.
* Use `{{ $variable }}` to output the loop variable inside the HTML.
