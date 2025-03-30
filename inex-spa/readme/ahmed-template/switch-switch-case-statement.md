# @switch - Switch Case Statement

The `@switch` directive in Ahmed PHP Template Engine allows you to execute different blocks of code based on the value of an expression, similar to a traditional `switch` statement in PHP.

### Syntax

```
@switch(expression)
    @case(value)
        // Code to execute
        @break
    @case(value2)
        // Another case
        @break
    @default
        // Default case if no match is found
@endswitch
```

### Example

```
@switch($status)
    @case('active')
        <p>Status: Active</p>
        @break
    @case('pending')
        <p>Status: Pending</p>
        @break
    @case('inactive')
        <p>Status: Inactive</p>
        @break
    @default
        <p>Status: Unknown</p>
@endswitch
```

### Output (if `$status = 'pending'`)

```
<p>Status: Pending</p>
```

### Notes

* The `@case` directive is used to define each case.
* The `@break` directive prevents fall-through behavior.
* The `@default` directive is optional and executes if no cases match.
