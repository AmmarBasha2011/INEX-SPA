# clear:routes

To delete all routes in your application, you can use the following command:

```php
php ammar clear:routes
```

### Important Note

Please be aware that executing this command will permanently delete all existing routes within the application. This includes:

* **Normal Routes**: Common routes defined within the application.
* **Dynamic Routes**: Routes generated dynamically during application runtime.
* **RequestTypeRoute**: Specific routes handling various request types.
* **Other Routes**: Any additional custom or plugin-based routes not classified above.

Running this command will reset the entire routing configuration, and consequently, all application pages will revert to a default state, effectively showing no content or endpoints.

### Precaution

Before proceeding with this command, ensure that you have properly backed up your routing configuration or database, if necessary, to prevent any accidental data loss.

Consider consulting with your development team or reviewing documentation to fully understand the impact and ensure this action
