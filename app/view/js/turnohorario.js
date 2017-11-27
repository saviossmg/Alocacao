/*
 * Criado por: Sávio Martins Valentim
 * Data: 06/06/2017
 */

var dir = "app/controller/turnohorario/";

// salva o registro
function salvarRegistro() {
    var check;
    if($("#id").val()!= ""){
        check = true;
    }
    else{
        check = validar();
    }
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var id = $("#id").val();
        var turno = $("#turno").val();
        var horaainicio = $("#horaainicio").val();
        var horaafim = $("#horaafim").val();
        var horabinicio = $("#horabinicio").val();
        var horabfim = $("#horabfim").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            turno: turno,
            horaainicio: horaainicio,
            horaafim: horaafim,
            horabinicio: horabinicio,
            horabfim: horabfim
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#turnohorario_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listar();
                // clear fields from the popup
                $("#id").val(null);
                $("#turno").val("");
                $("#horaainicio").val("");
                $("#horaafim").val("");
                $("#horabinicio").val("");
                $("#horabfim").val("");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        });
    }
    else {
        //simula um submit para mostrar os campos que estão invalidos e as respectivas entradas
        var event = jQuery.Event("submit");
        $("form:first").trigger(event);
        if (event.isDefaultPrevented()) {
            validar();
        }
    }
}

// Lista todos os registros
function listar() {
    $.get(dir + "listar.php", {}, function (data, status) {
        $(".records_content_turnoshorarios").html(data);
        $('#turnoshorarios').dataTable({
            "lengthMenu": [[5, 10, 15], [5, 10, 15]],
            "oLanguage": {
                "sProcessing": "Processando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Não foram encontrados resultados",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sPrevious": "Anterior",
                    "sNext": "Seguinte",
                    "sLast": "Último"
                }
            },
        });
    });
}

function deletar(id) {
    bootbox.confirm("Deseja realmente apagar esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "deletar.php", {
                    id: id
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listar();
                    }
                    else {
                        //mostra mensagem de falha
                        bootbox.alert(retorno.mensagem);
                    }
                }
            );
        }
    });
}

//captura os dados para a edição atraves do ID
function editar(id) {
    $("#id").val(id);
    $.post(dir + "detalhar.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            if (retorno.status) {
                //Coloca os valores do retorno nos campos devido
                $("#turno").val(retorno.data.turno);
                $("#horaainicio").val(retorno.data.horaainicio);
                $("#horaafim").val(retorno.data.horaafim);
                $("#horabinicio").val(retorno.data.horabinicio);
                $("#horabfim").val(retorno.data.horabfim);
                validar();
                // abre o modal
                $("#turnohorario_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

function comboTurno(){
    $('#turno').empty(); //remove all child nodes
    $.post("app/controller/registro/carregarCombo.php", {
            identidade: 5,
            idpai: null
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#turno").append(data);
        });
}

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listar(); // chama a função
    comboTurno();
    $('#turnoshorarios').dataTable();
    $('#turnohorario_modal').on('hide.bs.modal', function () {
        $('#turnohorario').bootstrapValidator("resetForm",true);
        $('#turnohorario').each(function () {
            this.reset();
            $("#id").val(null);
        });
    });
    $('#horaainicio').mask('00:00');
    $('#horaafim').mask('00:00');
    $('#horabinicio').mask('00:00');
    $('#horabfim').mask('00:00');
});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#turnohorario').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            turno: {
                validators: {
                    notEmpty: {
                        message: 'O turno é requerido'
                    }
                }
            },
            horaainicio: {
                validators: {
                    notEmpty: {
                        message: 'O horário 1 - Início é requerido.'
                    },
                    callback: {
                        message: 'O horário 1 - Início não é válido.',
                        callback: function (value, validator, $field) {
                            return (value != null && value.length == 5);
                        }
                    }
                }
            },
            horaafim: {
                validators: {
                    notEmpty: {
                        message: 'O horário 1 - Final é requerido.'
                    },
                    callback: {
                        message: 'O horário 1 - Final não é válido.',
                        callback: function (value, validator, $field) {
                            return (value != null && value.length == 5);
                        }
                    }
                }
            },
            horabinicio: {
                validators: {
                    notEmpty: {
                        message: 'O horário 2 - Início é requerido.'
                    },
                    callback: {
                        message: 'O horário 2 - Início não é válido.',
                        callback: function (value, validator, $field) {
                            return (value != null && value.length == 5);
                        }
                    }
                }
            },
            horabfim: {
                validators: {
                    notEmpty: {
                        message: 'O horário 2 - Final é requerido.'
                    },
                    callback: {
                        message: 'O horário 2 - Final não é válido.',
                        callback: function (value, validator, $field) {
                            return (value != null && value.length == 5);
                        }
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#turnohorario').data('bootstrapValidator').isValid();
}