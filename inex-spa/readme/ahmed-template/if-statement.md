# If Statement

The **If Statement** in Ahmed Template Engine for INEX SPA allows you to conditionally render content based on logical expressions, similar to traditional PHP if-statements but in a cleaner, template-friendly syntax.

### Syntax

```php
@if(condition)
    <!-- Code to execute if the condition is true -->
@endif
```

#### Example:

```php
@if($user)
    <p>Welcome, {{$user['name']}}!</p>
@endif
```

This will display the welcome message only if the `$user` variable is set.

### Else Condition

To execute alternative code when the condition is false, use `@else`:

```php
@if($isLoggedIn)
    <p>You are logged in.</p>
@else
    <p>Please log in.</p>
@endif
```

### Else If (`@elseif`)

For multiple conditions, use `@elseif`:

```php
@if($score >= 90)
    <p>Excellent!</p>
@elseif($score >= 75)
    <p>Good Job!</p>
@else
    <p>Keep Trying!</p>
@endif
```

### Nested If Statements

You can nest `@if` statements for more complex logic:

```php
@if($user)
    @if($user['isAdmin'])
        <p>Welcome, Admin {{$user['name']}}!</p>
    @else
        <p>Welcome, {{$user['name']}}!</p>
    @endif
@endif
```

### Using Logical Operators

You can use logical operators in conditions:

```php
@if($user && $user['isVerified'])
    <p>Your account is verified.</p>
@endif

@if(!$user)
    <p>Guest Mode Active</p>
@endif
```

### Conclusion

The **If Statement** in Ahmed Template Engine provides a simple and efficient way to control content visibility based on conditions, making templates more dynamic and user-friendly.
