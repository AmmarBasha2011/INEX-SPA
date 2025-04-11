# Using PHP's Built-in Server

```bash
php -S localhost:8000
```

Then visit [http://localhost:8000](http://localhost:8000)

**Important Note:** When using PHP's built-in server:

* Manual class loading may be required as automatic framework loading only works with Apache
* Some framework features might be limited
* Recommended for development only, not production use
* Apache server provides full framework functionality

Production deployments should use Apache or another production-grade web server.
