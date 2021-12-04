$(document).ready(function () {
    $.spinner = new jQuerySpinner({
        parentId: 'page-top'
    });


    $("#btnEntrar").click(function () {
        $.spinner.show();
        request.ajax(
            "/login",
            {
                'username': $("#username").val(),
                'password': $("#password").val(),
            },
            function (response) {
                $.spinner.hide();
                if (response.success) {
                    window.location.replace('/portal/');
                }
            },
            function (response) {
                message: response.message
                return;
            },
        )
    });
});

