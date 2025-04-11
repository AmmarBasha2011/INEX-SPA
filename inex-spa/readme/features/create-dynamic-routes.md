# Create Dynamic Routes

Now, you can create dynamic routes by:

create a file named like this \[RouteName]\_dynamic.ahmed.php like

* post\_dynamic.ahmed.php
* blog\_dynamic.ahmed.php

You can get a data by this

```php
echo $_GET['data']; // 1
```

and user can access by `[URL]/[FileName] without _dynamic.ahmed.php/[data]` like

* http://localhost/post/1
* http://localhost/blog/2
