# ForEach

The **ForEach Loop** in Ahmed Template Engine for INEX SPA is used to iterate over arrays or collections, allowing dynamic rendering of lists and repeated elements.

### Syntax

```php
@foreach($array as $item)
    <!-- Code to execute for each item in the array -->
@endforeach
```

#### Example:

```php
@foreach($users as $user)
    <p>{{$user['name']}} - {{$user['email']}}</p>
@endforeach
```

This will loop through the `$users` array and display the name and email of each user.

### Using Index

If you need to access the index, use:

```php
@foreach($users as $index => $user)
    <p>#{{$index + 1}} - {{$user['name']}}</p>
@endforeach
```

### Checking for an Empty Array

Use `@empty` to handle cases where the array is empty:

```php
@empty($products)
<p>No products available.</p>
@else
@foreach($products as $product)
    <p>{{$product['name']}} - {{$product['price']}} USD</p>
@endforeach
@endempty # Or @endif
```

### Nesting ForEach Loops

You can nest loops for multi-dimensional arrays:

```php
@foreach($categories as $category)
    <h3>{{$category['name']}}</h3>
    <ul>
        @foreach($category['products'] as $product)
            <li>{{$product['name']}} - {{$product['price']}} USD</li>
        @endforeach
    </ul>
@endforeach
```

### Breaking Out of a Loop

To exit a loop early, use `@break`:

```php
@foreach($items as $item)
    @if($item['id'] == 5)
        @break
    @endif
    <p>{{$item['name']}}</p>
@endforeach
```

### Skipping an Iteration

To skip a specific iteration, use `@continue`:

```php
@foreach($numbers as $number)
    @if($number % 2 == 0)
        @continue
    @endif
    <p>{{$number}} is odd</p>
@endforeach
```

### Conclusion

The **ForEach Loop** in Ahmed Template Engine provides a powerful and flexible way to iterate over arrays, making template development more dynamic and efficient.
