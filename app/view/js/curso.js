/*
 * Criado por: Sávio Martins Valentim
 * Data: 06/06/2017
 */

var dir = "app/controller/curso/";

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
        var nome = $("#nome").val();
        var sigla = $("#sigla").val();
        var unidade = $("#unidade").val();
        var codigo = $("#codigo").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            nome: nome,
            sigla: sigla,
            unidade: unidade,
            codigo: codigo
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#curso_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
                listar();
                // clear fields from the popup
                $("#id").val(null);
                $("#nome").val("");
                $("#sigla").val("");
                $("#unidade").val("");
                $("#codigo").val("");
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
        $(".records_content_cursos").html(data);
        $('#cursos').dataTable({
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
                $("#sigla").val(retorno.data.sigla);
                $("#unidade").val(retorno.data.unidade);
                $("#codigo").val(retorno.data.codigo);
                validar();
                // abre o modal
                $("#curso_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

function detalhes(ident){
    $.post(dir+"detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            //Coloca os valores do retorno nos campos devido
            var nome = retorno.data.nome;
            //cria um form com os parametros
            var postFormStr = "<form method='post' action='cursoDetalhes.php'>\n";
            postFormStr += "<input type='hidden' name='idcursoparam' value='"+ident+"'/>";
            postFormStr += "<input type='hidden' name='nomecursoparam' value='"+nome+"'/>";
            postFormStr += "</form>";
            var formElement = $(postFormStr);
            $('body').append(formElement);
            $(formElement).submit();
        }
    );
}

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listar(); // chama a função
    $('#cursos').dataTable();
    $('#curso_modal').on('hide.bs.modal', function () {
        $('#curso').bootstrapValidator("resetForm",true);
        $('#curso').each(function () {
            this.reset();
            $("#id").val(null);
        });
    });
});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#curso').bootstrapValidator({
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
            sigla: {
                validators: {
                    notEmpty: {
                        message: 'O sigla é requerida.'
                    }
                }
            },
            unidade: {
                validators: {
                    notEmpty: {
                        message: 'A unidade é requerida'
                    }
                }
            },
            codigo: {
                validators: {
                    notEmpty: {
                        message: 'O código é requerido'
                    },
                    digits: {
                        message: 'O código é numerico'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#curso').data('bootstrapValidator').isValid();
}