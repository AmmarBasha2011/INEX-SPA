# Update Json/AuthParams.json

To update json data in `Json/AuthParams.json` file, you should follow these steps:

## Syntax

```json
{
    "fieldName": {"Argument": "Value", "Argument": true},
    "nextFieldName": {"Arrgument": "Value", "Argument": false}
}
```

## Example Syntax

```json
{
    "name": {"type": "text", "required": true, "maxLength": 60},
    "username": {"type": "text", "unique": true, "required": true, "maxLength": 60, "minLength": 10},
    "email": {"type": "email", "unique": true, "required": true, "shouldEnd": ["gmail.com", "outlook.com"], "shouldNotStart": ["inex.own", "ammarelkhteb"], "shouldNotEnd": ["localhost", "127.0.0.1"], "notEqual": ["inex.own@gmail.com", "ammarelkhteb@gmail.com"] , "maxLength": 100, "minLength": 10},
    "phoneNumber": {"type": "text", "unique": true, "shouldStart": ["+20", "+1"], "notEqual": ["+201096730619"]},
    "age": {"type": "number", "min": 18, "max": 60},
    "isCompany": {"type": "bool", "required": true, "default": false},
    "domain": {"type": "domain", "maxLength": 50, "subDomain": true, "subDir": true, "shouldEnd": [".com", ".org"], "notEqual": ["https://inexteam.blogspot.com", "https://github.com/AmmarBasha2011/INEX-SPA"]},
    "password": {"type": "text", "required": true, "default": "0123456789", "maxLength": 60, "minLength": 8},
    "inviteCode": {"type": "number", "required": true, "minLength": 6, "maxLength": 6, "equal": ["012345", "123456", "234567", "345678", "456789"]}
}
```

## Arguments

There are a lot of Arguments in `Auth` feature:

### type

This is argument have a lot of values:

#### text

If this is argument value is text

#### email

If this is argument value is email

#### number

If this is argument value is number

#### bool

If this is argument value is boolean

#### domain

If this is argument value is domain/link

### required

If argument value is required/Not Null like `email` or `password`&#x20;

Work with all types

### maxLength

This is the maximum number of characters in this argument value

Work with all types (except `bool` )

### unique

If argument value is unique

Work with all types

### minLength

This is minimum number of characters in this argument value

Work will all types (except `bool`)

### shouldEnd

This is list have words, argument value should end with one of it

Work will all types (except `bool`)

### shouldNotStart

This is list have words, argument value shouldn't start with them

Work will all types (except `bool`)

### shouldNotEnd

This is list have words, argument value shouldn't end with them

Work will all types (except `bool`)

### notEqual

This is list have values, argument value shouldn't equal them

Work will all types (except `bool` (Not Recommended))

### shouldStart

This is list have words, argument value should start with them

Work with all types (except `bool`)

### min

This is number, argument value mustn't less than this

Work with `number` type

### max

This is number, argument value mustn't over than this

Work with `number` type

### default

This is default value if argument value is null

Work with all types

### subDomain

This is bollean for check if allow this is sub domain

Work with `domain` type

### subDir

This is bollean for check if allow this is sub directory

Work with `domain` type

### equal

This is list have values, argument value should equal one of them

Work with all types (except `bool` (Not Recommended))
