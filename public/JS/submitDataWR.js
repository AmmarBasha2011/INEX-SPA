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