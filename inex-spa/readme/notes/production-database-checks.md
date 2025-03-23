# Production Database Checks

The `DB_CHECK` setting should be disabled in production:

```ini
# Development only - Enable DB checks
DB_CHECK=true

# Production - Disable DB checks
DB_CHECK=false
```

Reasons to disable in production:

* Significant performance impact
* Unnecessary file system operations
* Increased server load
* Slower response times
* Security considerations
