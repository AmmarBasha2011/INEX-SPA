# Using code

Second Method:

## Using code

* 1- Create any `.ahmed.php` file in `web` folder

```php
<?php
# web/generate.ahmed.php
$action = "create";
$table = "users";
$timestamp = date('Y_m_d_H_i_s');
$filename = "{$action}{$table}Table_{$timestamp}.sql";
$filePath = __DIR__ . '/../db/' . $filename;
$sqlTemplate = UserAuth::generateSQL();

file_put_contents($filePath, $sqlTemplate);
echo "DB file created: $filename\n";
?>
```

* 2- open this page on browser to run code
