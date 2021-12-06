$(document).ready(function () {
    $('#cnpj').mask('99.999.999/9999-99');
    $('#ddd_telefone').mask('(00)');
    $('#telefone').mask('0000-0000');
    $('#ddd_celular').mask('(00)');
    $('#celular').mask('00000-0000');
    $('#cep').mask('00000-000');

    function limpa_cep() {
        $("#rua").val("");
        $('#rua').prop("disabled", false);
        $("#numero").val("");
        $('#numero').prop("disabled", false);
        $("#complemento").val("");
        $('#complemento').prop("disabled", false);
        $("#bairro").val("")
        $('#bairro').prop("disabled", false);
        $("#municipio").val("")
        $('#municipio').prop("disabled", false);
        $("#estado").val("")
        $('#estado').prop("disabled", false);
        $("#cidade").val("")
        $('#cidade').prop("disabled", false);
    }

    $("#btnPesquisarCep").click(function () {
        if (!$('#cep').val() || $('#cep').val().length < 9) {
            bootbox.alert({
                message: "Preencha um CEP válido.",
                size: 'small',
            });
            return
        }
    });


    $("#btnFilter").click(function () {
        if (!$('#cnpj').val() || $('#cnpj').val().length <= 8) {
            bootbox.alert({
                message: "Preencha todos campos",
                size: 'small',
            });
            return;
        }

        $.spinner.show();
        request.ajax(
            "/portal/formEmpresa",
            {
                'cnpj': $("#cnpj").val().replace(/[^\d]+/gi, ''),
            },
            function (response) {
                $.spinner.hide();
                if (response.data) {
                    $("#razaoSocial").val(response.data.razao_social);
                    $("#nomeFantasia").val(response.data.nome_fantasia);
                    $("#ddd_telefone").val(response.data.ddd_telefone);
                    $("#telefone").val(response.data.telefone);
                    $("#ddd_celulare").val(response.data.ddd_celular);
                    $("#celular").val(response.data.celular);
                    $('#cnpj').attr("disabled", true);
                    $('#btnFilter').attr("hidden", true);
                    $('#infoCnpj').attr("hidden", true);
                    $('.removeClassCnpj').attr("hidden", true);
                    $("div").removeClass("center-cnpj");
                    $("div").removeClass("pesquisar");
                    $("main").removeClass("center-main-empresa");
                    $('#addClass').show();
                    $('#displayForm').show();
                }
            },
            function (response) {
                $.spinner.hide();
                bootbox.alert({
                    message: response.message
                });

            }
        )
    });

    $("#btnCadastrarEmpresa").click(function () {

        request.ajax(
            "/portal/createEmpresa",
            {
                'data': $("#FormCadastro").serialize(),
                'cnpj': $("#cnpj").val().replace(/[^\d]+/gi, ''),
                'cep': $("#cep").val(),
                'rua': $("#rua").val(),
                'bairro': $("#bairro").val(),
                'municipio': $("#municipio").val(),
                'estado': $("#estado").val(),
                'cidade': $("#cidade").val(),
                'complemento': $("#complemento").val(),
                'numero': $("#numero").val(),
            },
            {
                function(response) {
                    if (response.success) {
                        window.location.replace('/portal/usuario');
                    }
                }
            }
        )
    });

    $("#cep").keyup(function () {
        if ($('#cep').val().length < 9) {
            return
        }

        if (!$('#cep').val() || $('#cep').val().length < 9) {
            bootbox.alert({
                message: "Preencha um CEP válido.",
                size: 'small',
            });
            return
        }

        $.spinner.show()
        request.ajax(
            "/portal/httpClient",
            {
                'cep': $("#cep").val().replace(/[^\d]+/g, ''),
            },
            function (response) {
                $.spinner.hide();
                if (response.data.length > 0) {
                    $("#rua").val(response.data[0].endereco);
                    $("#bairro").val(response.data[0].bairro)
                    $("#municipio").val(response.data[0].cidade)
                    $("#estado").val(response.data[0].cidade_uf)
                    $("#cidade").val(response.data[0].estado)
                    $('#complemento').prop("disabled", false);
                    $('#numero').prop("disabled", false);
                    $('#rua').prop("disabled", false);
                    $('#bairro').prop("disabled", false);
                } else {
                    limpa_cep()
                }
            },
            function (response) {
                $.spinner.hide();
                bootbox.alert({
                    message: response.message
                });
                return;
            }
        )
    })
});
