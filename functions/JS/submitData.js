function submitData(route, inputIds=[], requestType="POST", redirect_route="") {
    var data = {};
    for (var i = 0; i < inputIds.length; i++) {
        data[inputIds[i]] = document.getElementById(inputIds[i]).value;
    }
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