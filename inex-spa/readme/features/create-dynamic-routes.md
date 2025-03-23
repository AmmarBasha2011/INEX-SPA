# Create Dynamic Routes

Now, you can create dynamic routes by:

create a file named like this \[RouteName]\_dynamic.php like

* post\_dynamic.php
* blog\_dynamic.php

You can get a data by this

```php
echo $_GET['data']; // 1
```

and user can access by `[URL]/[FileName] without _dynamin.php/[data]` like

* http://localhost/post/1
* http://localhost/blog/2
