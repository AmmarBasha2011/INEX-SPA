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