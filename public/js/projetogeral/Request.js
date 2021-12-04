const request = (function ($) {
    $.spinner = new jQuerySpinner({
        parentId: 'page-top',
    });

    'use strict';
    let fnSuccess,
        fnError;

    function onSuccess(response) {
        jQuery.spinner.hide();
        if (response.success && typeof fnSuccess === "function") {
            fnSuccess(response);
        } else {
            if (response.statusCode == '401') {
                if (response.message) {
                    bootbox.alert(response.message);
                } else {
                    bootbox.alert("Sua Sessão expirou. Favor relogar");
                }
            } else {
                bootbox.alert(response.message);
            }
        }
    }

    function onError(request, status, error) {
        jQuery.spinner.hide();
        if (error == "timeout") {
            bootbox.alert("A página não respondeu no tempo esperado. Favor tentar novamente!");
        } else {
            console.log(request);
            if (typeof fnError === "function") {
                fnError(request, status, error);
            }
        }
    }

    return {
        ajax: function (route, data, success, datatype, error, method = 'POST') {
            fnSuccess = success;
            fnError = error;
            return $.ajax({
                url: route,
                data: data,
                type: method,
                cache: false,
                success: onSuccess,
                datatype: 'json',
                error: onError,
                timeout: 60000,
                statusCode: {
                    404: function () {
                        jQuery.spinner.hide();
                        bootbox.alert("A página informada não foi encontrada!");
                    },
                    500: function () {
                        jQuery.spinner.hide();
                        bootbox.alert("Houve um erro interno. Favor tentar novamente!");
                    }
                }
            });
        },
    };

})(jQuery);
