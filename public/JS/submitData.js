/**
 * Submits form data to a specified server endpoint via AJAX and then redirects.
 *
 * This function gathers values from specified input fields, combines them with
 * any custom data and the CSRF token, and sends the payload to a given route using
 * an AJAX request. Upon a successful response, it triggers a client-side
 * redirect to a new route.
 *
 * @param {string} route - The server-side route to which the data will be submitted.
 * @param {string[]} [inputIds=[]] - An array of element IDs for the input fields whose
 *   values should be included in the submission.
 * @param {string} [requestType='POST'] - The HTTP method to use for the request.
 * @param {string} [redirect_route=''] - The route to redirect to upon successful submission.
 * @param {Object[]} [custom_values=[]] - An array of objects, where each object contains
 *   key-value pairs to be added to the submitted data.
 * @returns {void}
 */
function submitData(route, inputIds=[], requestType="POST", redirect_route="", custom_values=[]) {
    var data = {};
    for (var i = 0; i < inputIds.length; i++) {
        data[inputIds[i]] = document.getElementById(inputIds[i]).value;
    }
    custom_values.forEach((obj) => {
        Object.entries(obj).forEach(([key, value]) => {
            data[key] = value;
        });
    });
    data['csrf_token'] = document.getElementById('csrf_token').value;
    $.ajax({
        type: requestType,
        url: window.WEBSITE_URL + route,
        data: data,
        success: function (response) {
            // if (response.success) {
            //     window.location.href = redirect_link;
            // } else {
            //     alert(response.message);
            // }
            redirect(redirect_route);
        }
    });
}