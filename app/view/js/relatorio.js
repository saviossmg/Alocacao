/*
 * Criado por: Sávio Martins Valentim
 * Data: 05/11/2017
 */

var dir = "app/controller/relatorio/";

function impressao1(){
    var check = validar();
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        $("#impressao_espera").modal("show");
        // get values
        var semestre = $("#semestre").val();
        var salas = $("#salas").val();
        var campus = $("#unidade").val();
        $.post(dir + "identificacaoSalas.php", {
            salas: salas,
            semestre: semestre,
            campus: campus
        }, function (data){
            //captura o retorno da função
            $("#impressao_espera").modal("hide");
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#rel1_modal").modal("hide");

                // clear fields from the popup
                $("#semestre").val("");
                $("#unidade").val("");
                $("#salas").empty();
                $("#salas").selectpicker("refresh");

                //mostra a mensagem de sucesso
                window.open(retorno.data, 'popUpWindow','directories=no height=400, width=650, left=300, top=100, ' +
                    'resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes');

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

//função que vai imprimir
function imprimir(id) {
    if(id == 1){
        $("#rel1_modal").modal("show");
        mudaCampus();
    }
    if (id == 2){
        console.log("relatorio 2");
    }
    if (id == 3) {
        console.log("relatorio 3");
    }
}

function mudaCampus() {
    var uni = $("#unidade").val();
    if(uni == ""){
        $("#salas").empty();
        $("#salas").selectpicker("refresh");
    }
    else{
        $.post("app/controller/sala/carregarCombo2.php", {
                unidade: uni
            },
            function (data, status) {
                retorno = JSON.parse(data);
                if (retorno.status) {
                    $("#salas").empty();
                    $("#salas").selectpicker("refresh");
                    for (i = 0; i < Object.keys(retorno.data).length; i++)
                    {
                        $('#salas').append($('<option>',{
                                value: retorno.data[i].valor,
                                text : retorno.data[i].descricao,
                            }
                        ));
                    }
                    $("#salas").selectpicker("refresh");
                }
            }
        );
    }
}

// Lista todos os registros
function listar() {
    // get values
    $.get(dir + "listar.php", {}, function (data, status) {
        $(".records_content_relatorios").html(data);
        $('#relatorios').dataTable({
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

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listar(); // chama a função
    $('#relatorios').dataTable();
});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#relatorio1').bootstrapValidator({
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
            unidade: {
                validators: {
                    notEmpty: {
                        message: 'O campus é requerido.'
                    }
                }
            },
            salas: {
                validators: {
                    notEmpty: {
                        message: 'A sala é requerida.'
                    },
                    callback: {
                        message: 'Selecione ao menos 1 sala',
                        callback: function (value, validator, $field) {
                            /* Get the selected options */
                            var options = validator.getFieldElements('salas').val();
                            return (options != null && options.length >= 1);
                        }
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#relatorio1').data('bootstrapValidator').isValid();
};