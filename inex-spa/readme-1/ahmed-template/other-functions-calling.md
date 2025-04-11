# Other functions calling

You can call any function in framework or library in framework by use directive:

## Syntax 1

```php
@'FunctionName'('Arguments')
```

## Example

```php
# Use echo function
@echo("hi");
```

***

## Syntax 2

```php
@'FunctionName'()
```

## Example

### 1- Define a new function

```php
function ahmedSetLanguageToEnglish(){
    Language::set("en");
}
```

### 2- Use function

```php
@ahmedSetLanguageToEnglish()
```

## Syntax 3

```php
@'FunctionName'() # Or @'FunctionName'('Arguments')
```

## Example

### 1- Define a new function

```php
function ahmedCheckUser() {
    $user = 'ammar';
    if ($user == 'ammar') {
        echo 'Hi ammar';
    }
}
```

### 2- Use function

```php
@ahmedCheckUser()
```

## Notes

{% hint style="info" %}
Functions for Directives start with `ahmed`&#x20;
{% endhint %}

{% hint style="info" %}
This directive can't be use returned data

Solution: Use `Syntax 3`
{% endhint %}
