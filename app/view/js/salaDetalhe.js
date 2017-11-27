/*
 * Criado por: Sávio Martins Valentim
 * Data: 24/06/2017
 */

var dir = "app/controller/salaDetalhe/";

// salva o registro
function inserirRecurso() {
    var check;
    if($("#id").val()!= ""){
        check = true;
    }
    else{
        check = validarRecurso();
    }
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var id = $("#id").val();
        var recurso = $("#recurso").val();
        var quantidade = $("#quantidade").val();
        var sala = $("#idSala").val();

        // Add record
        $.post(dir + "inserirRecursos.php", {
            id: id,
            registro: recurso,
            quantidade: quantidade,
            sala: sala
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#sala_recurso_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listarRecurso();
                // clear fields from the popup
                comboRecurso();
                $("#id").val("");
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
            validarSala();
        }
    }
}

//captura os dados para a edição atraves do ID
function editarRecurso(id) {
    $("#id").val(id);
    $.post(dir + "detalharRecursos.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            if (retorno.status) {
                //Coloca os valores do retorno nos campos devido
                $("#recurso").val(retorno.data.registro);
                $("#quantidade").val(retorno.data.quantidade);
                validarRecurso();
                // abre o modal
                $("#sala_recurso_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

// Lista todos os registros de predios vinculados
function listarRecurso() {
    var idSala = $("#idSala").val();
    $.post(dir + "listarRecursos.php", {
        idSala: idSala
    }, function (data, status) {
        $(".records_content_recursossala").html(data);
        $('#recursosSala').dataTable({
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

function deletarRecurso(id) {
    bootbox.confirm("Deseja realmente desvincular esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "deletarRecursos.php", {
                    id: id
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        comboRecurso();
                        listarRecurso();
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

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listarRecurso(); // chama a função
    $('#equipamentosSala').dataTable();
    $('#sala_recurso_modal').on('hide.bs.modal', function () {
        $('#recursoSala').bootstrapValidator("resetForm",true);
        $('#recursoSala').each(function () {
            this.reset();
            $("#id").val("");
        });
    });
    comboRecurso();
});

//carrega as combos
function comboRecurso(){
    $('#recurso').empty(); //remove all child nodes
    $.post("app/controller/registro/carregarCombo.php", {
            identidade: 3,
            idpai: 1
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#recurso").append(data);
        });
}


//valida os campos do form
function validarRecurso() {
    //instancia um validador de campos
    $('#recursoSala').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            recurso: {
                validators: {
                    notEmpty: {
                        message: 'O recurso é requerido.'
                    }
                }
            },
            quantidade: {
                validators: {
                    notEmpty: {
                        message: 'A quantidade é requerido.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#recursoSala').data('bootstrapValidator').isValid();
}

function voltar_saldet() {
    $("#main").load(dirview+"sala.php");
}