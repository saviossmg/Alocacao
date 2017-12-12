/*
 * Criado por: Sávio Martins Valentim
 * Data: 24/06/2017
 */

var dir = "app/controller/semestreletivo/";

// salva o registro
function salvarRegistro() {
    var check;
    if ($("#id").val() != "") {
        check = true;
    }
    else {
        check = validar();
    }
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var id = $("#id").val();
        var semestre = $("#semestre").val();
        var curso = $("#curso").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            semestre: semestre,
            curso: curso
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#semestreletivo_modal").modal("hide");

                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);

                // read records again
                listar();

                // clear fields from the popup
                $("#id").val("");
                $("#semestre").val("");
                $('.selectpicker').selectpicker('deselectAll');
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
    var idSemestre = $("#idSemestre").val();
    $.post(dir + "listar.php", {
        idSemestre: idSemestre
    }, function (data, status) {
        $(".records_content_semestresletivos").html(data);
        $('#semestresletivos').dataTable({
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

function detalhes(ident) {
    $.post(dir+"detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            //Coloca os valores do retorno nos campos devido
            var idsemestre = retorno.data.idsemestre;
            var semestre = retorno.data.semestre;
            var idcurso = retorno.data.idcurso;
            var curso = retorno.data.curso;
            //cria um form com os parametros
            $.post("app/view.php", {
                    'idsemestreletivo': ident,
                    'idsemestre': idsemestre,
                    'semestre': semestre,
                    'idcurso': idcurso,
                    'curso': curso
                },
                function (data) {
                    $("#main").html(data); //data is everything you 'echo' on php side
                }
            );
        }
    );
}

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listar(); // chama a função
    $('#semestresletivos').dataTable();
    $('#semestreletivo_modal').on('hide.bs.modal', function (event) {
        $('#semestreletivo').bootstrapValidator("resetForm", true);
        $('#semestreletivo').each(function () {
            this.reset();
            $("#id").val("");
            $('.selectpicker').selectpicker('deselectAll');

        })
    });
    $.post("app/controller/curso/carregarComboA.php",
        function (data, status) {
            $("#curso").empty();
            $("#curso").selectpicker("refresh");
            $('#curso').append(data);
            $("#curso").selectpicker("refresh");
        }
    );

});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#semestreletivo').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            semestre: {
                validators: {
                    notEmpty: {
                        message: 'O semestre é requerido.'
                    }
                }
            },
            curso: {
                validators: {
                    notEmpty: {
                        message: 'O curso é requerido.'
                    },
                    callback: {
                        message: 'Selecione ao menos 1 curso',
                        callback: function (value, validator, $field) {
                            /* Get the selected options */
                            var options = validator.getFieldElements('curso').val();
                            return (options != null && options.length >= 1);
                        }
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#semestreletivo').data('bootstrapValidator').isValid();
}