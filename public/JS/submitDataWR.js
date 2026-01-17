/**
 * Submits form data to a server endpoint via AJAX and returns the server's response.
 *
 * This function is similar to `submitData` but does not perform a client-side redirect.
 * Instead, it returns a Promise that resolves with the JSON response from the server,
 * allowing for custom handling of the response in the calling script.
 *
 * @param {string} route - The server-side route for the data submission.
 * @param {string[]} [inputIds=[]] - An array of element IDs for the input fields
 *   to be included in the submission.
 * @param {string} [requestType='POST'] - The HTTP method for the request.
 * @param {Object[]} [custom_values=[]] - An array of objects with custom key-value
 *   pairs to be added to the data payload.
 * @returns {Promise<Object>} A Promise that resolves with the JSON response from the
 *   server on success, or rejects with an error on failure.
 */
function submitDataWR(route, inputIds=[], requestType="POST", custom_values=[]) {
    return new Promise((resolve, reject) => {
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
            dataType: 'json',
            success: function (response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}
