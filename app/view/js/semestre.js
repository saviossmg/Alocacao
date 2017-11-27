/*
 * Criado por: Sávio Martins Valentim
 * Data: 24/06/2017
 */

var dir = "app/controller/semestre/";

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
        var datainicio = $("#datainicio").val();
        var datafim = $("#datafim").val();

        // Add record
        $.post(dir + "inserir.php", {
            id: id,
            descricao: descricao,
            datainicio: datainicio,
            datafim: datafim
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if(retorno.status){
                // close the popup
                $("#semestre_modal").modal("hide");

                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);

                // read records again
                listar();

                // clear fields from the popup
                $("#id").val("");
                $("#descricao").val("");
                $("#datainicio").val("");
                $("#datafim").val("");
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
    $.get(dir + "listar.php", {}, function (data, status) {
        $(".records_content_semestres").html(data);
        $('#semestres').dataTable({
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
                $("#datainicio").val(retorno.data.datainicio);
                $("#datafim").val(retorno.data.datafim);
                // abre o modal
                $("#semestre_modal").modal("show");
            }
            else{
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
            var descricao = retorno.data.descricao;
            //cria um form com os parametros
            var postFormStr = "<form method='post' action='semestreLetivo.php'>\n";
            postFormStr += "<input type='hidden' name='idsemestreparam' value='"+ident+"'/>";
            postFormStr += "<input type='hidden' name='descsemestreparam' value='"+descricao+"'/>";
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
    $('#semestres').dataTable();
    $('#semestre_modal').on('hide.bs.modal', function (event) {
        $('#semestre').bootstrapValidator("resetForm",true);
        $('#semestre').each(function () {
            this.reset();
            $("#id").val("");
        })
    });
    $('#datainicio').mask('00/00/0000');
  /*  $('#datainicio').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "-100:+0",
        format: 'dd/mm/yyyy',
        language: "pt-BR",
        todayHighlight: true
    });*/
    $('#datafim').mask('00/00/0000');
    $('#descricao').mask('0000/0');

});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#semestre').bootstrapValidator({
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
                    },
                    callback: {
                        message: 'A descrição nao é valida',
                        callback: function (value, validator, $field) {
                            /* Get the selected options */
                            return (value != null && value.length == 6);
                        }
                    }
                }
            },
            datainicio: {
                validators: {
                    notEmpty: {
                        message: 'A data de início é requerida.'
                    },
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'A data de início nao é valida'
                    }
                }
            },
            datafim: {
                validators: {
                    notEmpty: {
                        message: 'A data de término  é requerida.'
                    },
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'A data de término nao é valida'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#semestre').data('bootstrapValidator').isValid();
}