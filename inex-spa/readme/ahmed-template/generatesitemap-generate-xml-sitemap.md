# @generateSitemap - Generate XML Sitemap

The `@generateSitemap` directive in Ahmed PHP Template Engine allows you to create an XML sitemap automatically for SEO purposes.

### Syntax

```php
@generateSitemap(outputPath)
```

* `outputPath`: (Optional) The file path where the sitemap should be saved. Defaults to `sitemap.xml` in the root directory.

### Description

* This directive scans the routes and generates an XML sitemap.
* Helps search engines index the website more efficiently.
* Supports automatic updating when new routes are added.

### Example

```php
@generateSitemap('public/sitemap.xml')
```

### Expected Behavior

* An XML file containing all indexed routes is generated.
* If no path is specified, it defaults to `sitemap.xml` in the root directory.
* Can be triggered manually or scheduled via a cron job.

### Notes

* Ensure write permissions are set for the output directory.
* Useful for improving search engine optimization (SEO).
* Can be combined with caching to prevent unnecessary regenerations.
