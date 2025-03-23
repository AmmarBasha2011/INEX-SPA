# Cache Operations

```bash
# Create cache entry
php ammar make:cache -1 key -2 value -3 expirationSeconds
# Example: php ammar make:cache -1 user_profile -2 "data" -3 3600

# Get cache value
php ammar get:cache -1 key
# Example: php ammar get:cache -1 user_profile

# Update cache
php ammar update:cache -1 key -2 newValue
# Example: php ammar update:cache -1 user_profile -2 "updated_data"

# Delete cache entry
php ammar delete:cache -1 key
# Example: php ammar delete:cache -1 user_profile
```
