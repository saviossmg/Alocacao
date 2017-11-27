/*
 * Criado por: Sávio Martins Valentim
 * Data: 03/06/2017
 */

var dir = "app/controller/registro/";

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
        var identidade = $("#identidade").val();
        var descricao = $("#descricao").val();
        var ativo = $("#ativo").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            identidade: identidade,
            descricao: descricao,
            ativo: ativo,
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if(retorno.status){
                // close the popup
                $("#tiposala_modal").modal("hide");

                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);

                // read records again
                listar();

                // clear fields from the popup
                $("#id").val("");
                $("#descricao").val("");
                $("#ativo").val(1);
            }
            else{
                bootbox.alert(retorno.mensagem);
            }
        });
    }
    else{
        //simula um submit para mostrar os campos que estão invalidos e as respectivas entradas
        var event = jQuery.Event( "submit" );
        $( "form:first" ).trigger( event );
        if ( event.isDefaultPrevented() ) {
            validar();
        }
    }
}

// Lista todos os registros
function listar() {
    // get values
    $.post(dir + "listar.php", {
        identidade: 1,
        tipo: 'salas'
    }, function (data) {
        $(".records_content_tipossala").html(data);
        $('#tipossalas').dataTable({
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
                    if(retorno.status){
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listar();
                    }
                    else{
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
            if(retorno.status){
                //Coloca os valores do retorno nos campos devidos
                $("#descricao").val(retorno.data.descricao);
                $("#ativo").val(retorno.data.ativo);
                // abre o modal
                $("#tiposala_modal").modal("show");
            }
            else{
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}


$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listar(); // chama a função
    $('#tipossalas').dataTable();
    $('#tiposala_modal').on('hide.bs.modal', function (event) {
        $('#tipo_sala').bootstrapValidator("resetForm",true);
        $('#tipo_sala').each(function () {
            this.reset();
            $("#id").val("");
        })
    });
});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#tipo_sala').bootstrapValidator({
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
                        message: 'A descrição é requerido.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#tipo_sala').data('bootstrapValidator').isValid();
}