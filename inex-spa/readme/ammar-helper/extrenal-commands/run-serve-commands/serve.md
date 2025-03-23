# serve

You can quickly start a development server using the built-in PHP server:

```bash
php ammar serve
```

This will launch the application at [http://localhost:8000](http://localhost:8000) by default.

To use a custom port:

```bash
php ammar serve -1 9000
```

**Important Note:** When using PHP's built-in server:

* Manual class loading may be required as automatic framework loading only works with Apache
* Some framework features might be limited
* Recommended for development only, not production use
* Apache server provides full framework functionality

Production deployments should use Apache or another production-grade web server.
