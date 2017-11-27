/*
 * Criado por: Sávio Martins Valentim
 * Data: 24/06/2017
 */

var dir = "app/controller/predioDetalhe/";

// salva o registro
function vincularSala() {
    var check;
    check = validarSala();
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var sala = $("#sala").val();
        var idPredio = $("#idPredio").val();

        // Add record
        $.post(dir + "vincularSalas.php", {
            sala: sala,
            idPredio: idPredio
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#predio_sala_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listarSalas();
                // clear fields from the popup
                comboSala();
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

// Lista todos os registros de predios vinculados
function listarSalas() {
    var idPredio = $("#idPredio").val();
    $.post(dir + "listarSalas.php", {
        idPredio: idPredio
    }, function (data, status) {
        $(".records_content_salaspredio").html(data);
        $('#salasPredio').dataTable({
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

function desvincularSala(id) {
    bootbox.confirm("Deseja realmente desvincular esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "desvincularSalas.php", {
                    id: id
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        comboSala();
                        listarSalas();
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

function detalhesSala(ident){
    $.post("app/controller/sala/detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            //Coloca os valores do retorno nos campos devido
            var nome = retorno.data.bloco;
            //cria um form com os parametros
            $.post("app/view/salaDetalhes.php", {
                    'nomesalaparam': nome,
                    'idsalaparam': ident
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
    listarSalas(); // chama a função
    $('#prediosUnidade').dataTable();
    $('#predio_sala_modal').on('hide.bs.modal', function () {
        $('#salaPredio').bootstrapValidator("resetForm",true);
        $('#salaPredio').each(function () {
            this.reset();
        });
    });
    comboSala();
});

//carrega as combos
function comboSala(){
    $('#sala').empty(); //remove all child nodes
    $.post("app/controller/sala/carregarCombo.php", {
            livre: 1
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#sala").append(data);
        });
}


//valida os campos do form
function validarSala() {
    //instancia um validador de campos
    $('#salaPredio').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            sala: {
                validators: {
                    notEmpty: {
                        message: 'A sala é requerida.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#salaPredio').data('bootstrapValidator').isValid();
}

function voltar_predet() {
    $("#main").load(dirview+"predio.php");
}