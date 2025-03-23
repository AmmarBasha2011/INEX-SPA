# Use Rate Limiter

## Rate Limiting Implementation Guide

To prevent API spamming, implement an effective rate limiting strategy. This document outlines steps to use the Rate Limiter feature, ensuring smooth and secure application performance.

### Steps to Implement Rate Limiting

#### 1. Update the Environment Configuration

Begin by updating your environment configuration file, usually named `.env`. Add or update the following configuration settings:

* **REQUESTS\_PER\_HOUR**: Set this to your desired number of requests per hour. For example, `REQUESTS_PER_HOUR=100` limits each client to 100 requests per hour.
* **USE\_RATELIMITER**: Enable the rate limiter by specifying `USE_RATELIMITER=true`.

#### 2. Integrate Rate Limiting in Your Application

Incorporate the rate limiting feature directly into your application's codebase. This ensures that your API is protected from unwanted traffic and potential abuse. Use the following line of code to integrate rate limiting based on the client's IP address:

```php
RateLimiter::check($_SERVER['REMOTE_ADDR']);
```

This function checks the number of requests from each IP address and restricts any excess within the defined limit.

#### Benefits of Rate Limiting

Implementing a robust rate limiting system helps in:

* **Preventing Abuse**: Protects your API from excessive use and potential DDoS attacks.
* **Resource Management**: Ensures efficient use of server resources by controlling load.
* **Enhanced Security**: Reduces risk by providing an additional layer of cybersecurity checks.

#### Conclusion

By following these steps, you ensure that your API is shielded from spam and misuse. Adjusting the `REQUESTS_PER_HOUR` value allows you to tailor the level of access based on your application's needs. Regularly monitor API usage to fine-tune these settings for
