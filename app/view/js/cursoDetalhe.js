/*
 * Criado por: Sávio Martins Valentim
 * Data: 24/06/2017
 */

var dir = "app/controller/cursoDetalhe/";

// salva o registro
function vincularDisciplina() {
    var check;
    check = validarDisciplina();
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var disciplina = $("#disciplina").val();
        var idPredio = $("#idPredio").val();

        // Add record
        $.post(dir + "vincularDisciplina.php", {
            disciplina: disciplina,
            idCurso: idPredio
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#curso_disciplina_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listarDisciplinas();
                // clear fields from the popup
                comboDisciplina();
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
            validarDisciplina();
        }
    }
}

// Lista todos os registros de predios vinculados
function listarDisciplinas() {
    var idCurso = $("#idCurso").val();
    $.post(dir + "listarDisciplinas.php", {
        idCurso: idCurso
    }, function (data, status) {
        $(".records_content_disciplinascurso").html(data);
        $('#cursoDisciplinas').dataTable({
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

function desvincularDisciplina(id) {
   bootbox.confirm("Deseja realmente desvincular esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "desvincularDisciplina.php", {
                    id: id
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        comboDisciplina();
                        listarDisciplinas();
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
    listarDisciplinas(); // chama a função
    $('#cursoDisciplinas').dataTable();
    $('#curso_disciplina_modal').on('hide.bs.modal', function () {
        $('#cursoDisciplina').bootstrapValidator("resetForm",true);
        $('#cursoDisciplina').each(function () {
            this.reset();
        });
    });
    comboDisciplina();
});

//carrega as combos
function comboDisciplina(){
    $('#disciplina').empty(); //remove all child nodes
    $.post("app/controller/disciplina/carregarCombo.php", {
        livre: 1
    },
    function (data, status) {
        //Coloca os valores do retorno nos campos devidos
        $("#disciplina").append(data);
    });
}


//valida os campos do form
function validarDisciplina() {
    //instancia um validador de campos
    $('#cursoDisciplina').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            disciplina: {
                validators: {
                    notEmpty: {
                        message: 'A disciplina é requerida.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#cursoDisciplina').data('bootstrapValidator').isValid();
}

function voltar_curdet() {
    $("#main").load(dirview+"curso.php");
}