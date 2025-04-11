# Delete Docs files

#### Documentation Cleanup in Production

When deploying your INEX SPA application to a production environment, it is **strongly recommended to delete the `Docs` folder**. Here’s why:

* The `Docs` folder contains **over 250 files**, while the core framework itself includes **fewer than 30 files**.
* These documentation files are bundled with the framework **to help developers** during development by providing:
  * Offline access to documentation
  * Easy editing or updating of documentation content

However, in a production environment:

* These files are **not required** for the application to function.
* They **increase deployment size** and may **expose internal structure or content** unnecessarily.

> ✅ **Best Practice:** Always remove the `Docs` folder before deploying your project to production to keep your application clean, lightweight, and secure.

***

#### Automatically Clear Documentation Files

You can safely remove all documentation files without affecting any core framework files or causing errors by using the built-in Ammar CLI command:

```bash
php ammar clear:docs
```

This command is designed to delete only the `Docs` folder, ensuring your application remains fully functional and optimized for production.
