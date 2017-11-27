/*
 * Criado por: Sávio Martins Valentim
 * Data: 12/06/2017
 */

var dir = "app/controller/unidadeDetalhe/";

// salva o registro
function vincularPredio() {
    var check;
    check = validarPredio();
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var predio = $("#predio").val();
        var idUnidade = $("#idUnidade").val();

        // Add record
        $.post(dir + "inserirPredio.php", {
            predio: predio,
            idUnidade: idUnidade
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#unidade_predio_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listarPredios();
                // clear fields from the popup
                comboPredio();
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
            validarPredio();
        }
    }
}

function vincularCurso() {
        // get values
        var curso = $("#curso").val();
        var idUnidade = $("#idUnidade").val();
        // Add record
        $.post(dir + "inserirCurso.php", {
            curso: curso,
            idUnidade: idUnidade
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#unidade_curso_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listarCursos();
                // clear fields from the popup
                comboCurso();
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        });
}

// Lista todos os registros de predios vinculados
function listarPredios() {
    var idUnidade = $("#idUnidade").val();
    $.post(dir + "listarPredios.php", {
        idUnidade: idUnidade
    }, function (data, status) {
        $(".records_content_prediosunidade").html(data);
        $('#prediosUnidade').dataTable({
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

// Lista todos os registros de cursos vinculados
function listarCursos() {
    var idUnidade = $("#idUnidade").val();
    $.post(dir + "listarCursos.php", {
        idUnidade: idUnidade
    }, function (data, status) {
        $(".records_content_cursosunidade").html(data);
        $('#cursosUnidade').dataTable({
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

function desvincularPredio(id) {
    bootbox.confirm("Deseja realmente desvincular esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "desvincularPredio.php", {
                    id: id
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        comboPredio();
                        listarPredios();
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

function desvincularCurso(id) {
    bootbox.confirm("Deseja realmente desvincular esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "desvincularCurso.php", {
                    id: id
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listarCursos();
                        comboCurso();
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

function detalhesPredio(ident){
    $.post("app/controller/predio/detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            //Coloca os valores do retorno nos campos devido
            var nome = retorno.data.nome;
            //cria um form com os parametros
            $.post("app/view/predioDetalhes.php", {
                    'nomepredioparam': nome,
                    'idpredioparam': ident
                },
                function (data) {
                    $("#main").html(data); //data is everything you 'echo' on php side
                }
            );
        }
    );
}

function detalhesCurso(ident){
    $.post("app/controller/curso/detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            //Coloca os valores do retorno nos campos devido
            var nome = retorno.data.nome;
            //cria um form com os parametros
            $.post("app/view/cursoDetalhes.php", {
                    'nomecursoparam': nome,
                    'idcursoparam': ident
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
    listarPredios(); // chama a função
    listarCursos();
    $('#prediosUnidade').dataTable();
    $('#cursosUnidade').dataTable();
    $('#unidade_predio_modal').on('hide.bs.modal', function () {
        $('#predioUnidade').bootstrapValidator("resetForm",true);
        $('#predioUnidade').each(function () {
            this.reset();
        });
    });
    $('#unidade_curso_modal').on('hide.bs.modal', function () {
        $('#cursoUnidade').bootstrapValidator("resetForm",true);
        $('#cursoUnidade').each(function () {
            this.reset();
        });
    });
    comboPredio();
    comboCurso();
});

//carrega as combos
function comboPredio(){
    $('#predio').empty(); //remove all child nodes
    $.post("app/controller/predio/carregarCombo.php", {
        livre: 1
    },
    function (data, status) {
        //Coloca os valores do retorno nos campos devidos
        $("#predio").append(data);
    });
}

function comboCurso() {
    $('#curso').empty(); //remove all child nodes
    $.post("app/controller/curso/carregarCombo.php", {
        livre: 1
    },
    function (data, status) {
        //Coloca os valores do retorno nos campos devidos
        $("#curso").append(data);
    });
}

//valida os campos do form
function validarPredio() {
    //instancia um validador de campos
    $('#predioUnidade').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            predio: {
                validators: {
                    notEmpty: {
                        message: 'O prédio é requerido.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#predioUnidade').data('bootstrapValidator').isValid();
}

//valida os campos do form
function validarCurso() {
    //instancia um validador de campos
    $('#cursoUnidade').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            curso: {
                validators: {
                    notEmpty: {
                        message: 'O curso é requerido.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#cursoUnidade').data('bootstrapValidator').isValid();
}

function voltar_unidet() {
    $("#main").load(dirview+"unidade.php");
}