# Check Request Type

Now, you can check request type from

* GET
* POST
* PUT
* DELETE
* PATCH

by create a file named like this \[RouteName]_request_\[RequestType].php like

* getUserData\_request\_GET.ahmed.php
* saveUserData\_request\_POST.ahmed.php

and user can access by `[URL]/[RouteName] without request_[RequestType]` like

* http://localhost/getUserData
* http://localhost/saveUserData
