$(document).ready(function () {
    $("#btnEnviar").click(function () {
        table.clear();
        request.ajax(
            "/portal/form",
            {
                'nome': $("#nome").val(),
                'endereco': $("#endereco").val(),
                'statusPortal': $("#statusPortal").val(),
            },
            function (response) {
                if (response.data) {
                    table.rows.add(response.data).draw();
                }
            },
        )
    });

    /**
     * AJAX - CRUD
     */
    $("#btnModalSalvar").click(function () {
        request.ajax(
            "portal/update",
            {
                'nome': $("#modalNome").val(),
                'rua': $("#modalRua").val(),
                'numero': $("#modalNumero").val(),
                'id_pessoa': $("#id_pessoa").val(),
                'id_endereco': $("#id_endereco").val()
            },
            function (response) {
                if (response.success == true) {
                    dialog.modal('hide');
                }
            }
        )
        $('#userModal').modal('hide')
    });

    $("#btnCadastro").click(function () {
        $("#formUser").trigger("reset");
        $(".modal-header").css("background-color", "#000000");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Criar novo usuario");
        $('#userModal').modal('show');
    });


    $("#btnAtualizar").click(function () {
        let data = table.row('.selected').data();
        if (!data) {
            bootbox.alert({
                message: "Selecione um usuário para alteração!",
                size: 'small'
            });
            return;
        }
        $('#userModal').modal('show');
        $("#modalNome").val(data.nome);
        $("#modalRua").val(data.rua);
        $("#modalNumero").val(data.numero);
        $("#id_pessoa").val(data.id_pessoa);
        $("#id_endereco").val(data.id_endereco);
        $(".modal-header").css("background-color", "rgb(73,73,73)");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Editar Usuario");
    })

    $('#table tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    const ativarInativar = function (data) {
        request.ajax(
            "portal/statusUsuario",
            {
                'id_pessoa': data.id_pessoa,
                'active': data.active,
            },
            function (response) {
                if (response.success) {
                    $("#btnEnviar").click();
                }
            },
        )
    }

    $('#table').on('change', 'input.editor-active', function () {
        let data = table.row($(this).parents('tr')).data();
        ativarInativar(data);
    });

    const table = $('#table').DataTable({
        "stateSave": true,
        "bFilter": false,
        "scrollX": true,
        "paging": false,
        "ordering": true,
        "info": false,
        "order": [[0, "asc"]],
        "columnDefs": [
            {className: "text-nowrap text-left ", "targets": [0]},
        ],
        'columnDefs': [{
            'targets': 4,
            'searchable': false,
            'orderable': false,
            'render': function (data, type, full, meta) {
                return '<input class="editor-active onclick=" id="checkbox" checked value="active" type="checkbox" >';
            }
        }],
        columns: [
            {data: 'id_pessoa'},
            {data: 'nome'},
            {data: 'endereco_completo'},
            {data: 'ts_inclusao'},
        ],
        'language': {
            "url": "../js/datatable/locale/pt-br.json",
        },
        rowCallback: function (row, data) {
            $('input.editor-active', row).prop('checked', data.active == 1);
        },
    });
});
