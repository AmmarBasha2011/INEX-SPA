# "Send POST, Cookie Found"

> "Send POST, Cookie Found"

The quote **"Send POST, Cookie Found"** refers to a unique security technique where:

* A **POST request** is sent with an **empty body**.
* The **data is stored in a cookie** instead of the request body.
* The **response is also empty**, but relevant data is placed in a cookie.

#### **Why is this effective?**

This approach disrupts traditional security tools like **BurpSuite Pro** that rely on:

* Intercepting **request bodies** for data manipulation.
* Analyzing **responses** for extracting sensitive information.

Since both the **request body and response are empty**, tools like BurpSuite struggle to detect or modify transmitted data, **enhancing security** and preventing certain attacks. 🚀🛡️
