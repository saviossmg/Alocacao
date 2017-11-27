/*
 * Criado por: Sávio Martins Valentim
 * Data: 09/06/2017
 */

var dir = "app/controller/disciplina/";

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
        var descricao = $("#descricao").val();
        var codcurricular = $("#codcurricular").val();
        var periodo = $("#periodo").val();
        var curso = $("#curso").val();
        var prerequisito = $("#prerequisito").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            descricao: descricao,
            codcurricular: codcurricular,
            periodo: periodo,
            curso: curso,
            prerequisito: prerequisito
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#disciplina_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listar();
                // clear fields from the popup
                $("#id").val(null);
                $("#descricao").val("");
                $("#codcurricular").val("");
                $("#periodo").val("");
                $("#curso").val("");
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
        $(".records_content_disciplinas").html(data);
        $('#disciplinas').dataTable({
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
                $("#descricao").val(retorno.data.descricao);
                $("#codcurricular").val(retorno.data.codcurricular);
                $("#periodo").val(retorno.data.periodo);
                $("#curso").val(retorno.data.curso);
                $("#prerequisito").val(retorno.data.prerequisito);
                validar();
                // abre o modal
                $("#disciplina_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

function detalhes(ident){
    //cria um form com os parametros
    var postFormStr = "<form method='post' action='disciplinaDetalhes.php'>\n";
    postFormStr += "<input type='hidden' name='idCurso' value='"+ident+"'/>";
    postFormStr += "</form>";
    var formElement = $(postFormStr);
    $('body').append(formElement);
    $(formElement).submit();
}

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listar(); // chama a função
    $('#disciplinas').dataTable();
    $('#disciplina_modal').on('hide.bs.modal', function () {
        $('#disciplina').bootstrapValidator("resetForm",true);
        $('#disciplina').each(function () {
            this.reset();
            $("#id").val(null);
        });
    });
});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#disciplina').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            descricao: {
                validators: {
                    notEmpty: {
                        message: 'A descrição é requerida.'
                    }
                }
            },
            codcurricular: {
                validators: {
                    notEmpty: {
                        message: 'O código curricular é requerido'
                    },
                    digits: {
                        message: 'O código curricular é numerico'
                    }
                }
            },
            periodo: {
                validators: {
                    notEmpty: {
                        message: 'O périodo  é requerido'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#disciplina').data('bootstrapValidator').isValid();
}