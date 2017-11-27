/*
 * Criado por: Sávio Martins Valentim
 * Data: 24/05/2017
 */

var dir = "app/controller/unidade/";

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
        var nome = $("#nome").val();
        var endereco = $("#endereco").val();
        var cep = $("#cep").val();
        var latitude = $("#latitude").val();
        var longitude = $("#longitude").val();
        var ativo = $("#ativo").val();
        var diretorgeral = $("#diretorgeral").val();
        var administrador = $("#administrador").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            nome: nome,
            endereco: endereco,
            cep: cep,
            latitude: latitude,
            longitude: longitude,
            ativo: ativo,
            diretorgeral: diretorgeral,
            administrador: administrador,
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#unidade_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listar();
                // clear fields from the popup
                $("#id").val(null);
                $("#nome").val("");
                $("#endereco").val("");
                $("#cep").val("");
                $("#latitude").val("");
                $("#longitude").val("");
                $("#ativo").val("1");
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
        $(".records_content_unidades").html(data);
        $('#unidades').dataTable({
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
                $("#nome").val(retorno.data.nome);
                $("#endereco").val(retorno.data.endereco);
                $("#cep").val(retorno.data.cep);
                $("#latitude").val(retorno.data.latitude);
                $("#longitude").val(retorno.data.longitude);
                $("#ativo").val(retorno.data.ativo);
                $("#diretorgeral").val(retorno.data.diretorgeral);
                $("#administrador").val(retorno.data.administrador);
                validar();
                // abre o modal
                $("#unidade_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

function detalhes(ident) {
    $.post(dir + "detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            //Coloca os valores do retorno nos campos devido
            var nome = retorno.data.nome;
            $.post("app/view/unidadeDetalhes.php", {
                    'nomeunidadeparam': nome,
                    'idunidadeparam': ident
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
    $('#unidades').dataTable();
    $('#unidade_modal').on('hide.bs.modal', function () {
        $('#unidade').bootstrapValidator("resetForm", true);
        $('#unidade').each(function () {
            this.reset();
            $("#id").val(null);
        });
    });
});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#unidade').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            nome: {
                validators: {
                    notEmpty: {
                        message: 'O nome é requerido.'
                    }
                }
            },
            endereco: {
                validators: {
                    notEmpty: {
                        message: 'O endereço é requerido'
                    }
                }
            },
            cep: {
                validators: {
                    notEmpty: {
                        message: 'O cep é requerido'
                    },
                    digits: {
                        message: 'O cep é numerico'
                    }
                }
            },
            latitude: {
                validators: {
                    numeric: {
                        message: 'A latitude é númerica'
                    }
                }
            },
            longitude: {
                validators: {
                    numeric: {
                        message: 'o longitude é numerica'
                    }
                }
            },
            diretorgeral: {
                validators: {
                    notEmpty: {
                        message: 'Selecione o Diretor Geral'
                    }
                }
            },
            administrador: {
                validators: {
                    notEmpty: {
                        message: 'Selecione o Administrador'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#unidade').data('bootstrapValidator').isValid();
}