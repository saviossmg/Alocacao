/*
 * Criado por: Sávio Martins Valentim
 * Data: 12/09/2017
 */

var dir = "app/controller/alocacao/";
var dirLab = "app/controller/laboratorio/";

//ALOCACAO
function alocarOferta() {
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
        var oferta = $("#oferta").val();
        var idsemestreletivo = $("#idSemestreletivo").val();
        var diasemana = $("#diasemana").val();
        var turno = $("#turno").val();
        var sala = $("#sala").val();
        var horario = $("#horario").val();

        // Add record
        $.post(dir + "salvarAlocacao.php", {
            id: id,
            oferta: oferta,
            idsemestreletivo: idsemestreletivo,
            diasemana: diasemana,
            turno: turno,
            sala: sala,
            horario: horario
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#oferta_modal").modal("hide");
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                // read records again
				listarOfertas();
                listarAlocacoes();
                // clear fields from the popup
                $("#id").val(null);
                $("#oferta").val(null);
                $("#diasemana").val("");
                $("#turno").val("");
                $("#sala").val("");
                $("#horario").val("");

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

//laboratorio
function salvarLaboratorio() {
    var check;
    if ($("#idlab").val() != "") {
        check = true;
    }
    else {
        check = validarLab();
    }
    //se a checagem for verdadeira, ele deixa passar o registro
    if (check) {
        // get values
        var id = $("#idlab").val();
        var observacao = $("#observacao").val();
        var semestre = $("#idSemestre").val();
        var lab = $("#lab").val();
        var tipouso = $("#tipouso").val();
        var turnolab = $("#turnolab").val();
        var dia = $("#dia").val();

        // Add record
        $.post(dirLab + "inserir.php", {
            id: id,
            observacao: observacao,
            semestre: semestre,
            lab: lab,
            tipouso: tipouso,
            turnolab: turnolab,
            dia: dia
        }, function (data, status) {
            //captura o retorno da função
            retorno = JSON.parse(data);
            if (retorno.status) {
                // close the popup
                $("#laboratorio_modal").modal("hide");

                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);

                // read records again
                listarLaboratorios();

                // clear fields from the popup
                $("#idlab").val("");
                $("#observacao").val("");
                $("#laboratorio").val("");
                $("#tipouso").val("");
                $("#turno").val("");
                $("#dia").val("");

            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        });
    }
    else {
        //simula um submit para mostrar os campos que estão invalidos e as respectivas entradas
        $("#laboratorio").submit(function(ev){ev.preventDefault();});
        $("#salvarlab").on("click", function(){
            var bootstrapValidator = $("#laboratorio").data('bootstrapValidator');
            bootstrapValidator.validate();
            if(bootstrapValidator.isValid())
                $("#salvarlab").submit();
            else return;

        });
        /*var event = jQuery.Event("submit");
        $("form:first").trigger(event);
        if (event.isDefaultPrevented()) {
            validarLab();
        }*/
    }

}

function editarLab(ident) {
    $("#idlab").val(ident);
    $.post(dirLab+"detalhar.php", {
            id: ident
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            if (retorno.status) {
                //Coloca os valores do retorno nos campos devido
                $("#lab").val(retorno.data.lab);
                $("#tipouso").val(retorno.data.tipouso);
                $("#turnolab").val(retorno.data.turnolab);
                $("#dia").val(retorno.data.dia);
                $("#observacao").val(retorno.data.observacao);
                validarLab();
                // abre o modal
                $("#laboratorio_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

function deletarLab(ident) {
    bootbox.confirm("Deseja realmente apagar esse regristro ?", function (result) {
        if (result) {
            $.post(dirLab+"deletar.php", {
                    id: ident
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listarLaboratorios();
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
function editarAlocacao(id) {
    $("#id").val(id);
    $.post(dir + "detalharAlocacao.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            if (retorno.status) {
                //Coloca os valores do retorno nos campos devido
                $("#oferta").val(retorno.data.oferta);
                $("#semestre").val(retorno.data.semestre);
                $("#disciplina").val(retorno.data.disciplina);
                $("#diasemana").val(retorno.data.diasemana);
                // abre o modal
                $("#oferta_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

function deletarAlocacao(ident) {
    bootbox.confirm("Deseja realmente apagar esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "deletarAlocacao.php", {
                    id: ident
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listarOfertas();
                        listarAlocacoes();
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

// Lista todos os registros
function listarAlocacoes() {
    var idCurso = $("#idCurso").val();
    var idSemestre = $("#idSemestre").val();
    $.post(dir + "listarAlocacao.php", {
        idCurso: idCurso,
        idSemestre: idSemestre
    }, function (data, status) {
        $(".records_content_alocacoes").html(data);
        $('#alocacoes').dataTable({
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

//OFERTAS
//detalha oferta e prepara para alocar
function prepararAlocacaoOferta(ident) {
    var idCurso = $("#idCurso").val();
    var idSemestre = $("#idSemestre").val();
    $.post(dir + "prepararAlocacao.php", {
            oferta: ident,
            semestre: idSemestre,
            curso: idCurso
        },
        function (data, status) {
            // PARSE json data
            var retorno = JSON.parse((data));
            if (retorno.status) {
                //Coloca os valores do retorno nos campos devido
                $("#oferta").val(retorno.data.oferta);
                $("#semestre").val(retorno.data.semestre);
                $("#disciplina").val(retorno.data.disciplina);
                $("#periodo").val(retorno.data.periodo);
                $("#diasemana").val(retorno.data.diasemana);
                // abre o modal
                $("#oferta_modal").modal("show");
            }
            else {
                bootbox.alert(retorno.mensagem);
            }
        }
    );
}

// Lista todos os registros
function listarOfertas() {
    var idCurso = $("#idCurso").val();
    var idSemestre = $("#idSemestre").val();
    $.post(dir + "listarOferta.php", {
        idCurso: idCurso,
        idSemestre: idSemestre
    }, function (data, status) {
        $(".records_content_ofertas").html(data);
        $('#ofertas').dataTable({
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

// Lista todos os registros
function listarLaboratorios() {
    var idSemestre = $("#idSemestre").val();
    $.post("app/controller/laboratorio/listar.php", {
        idSemestre: idSemestre
    }, function (data, status) {
        $(".records_content_laboratorio").html(data);
        $('#laboratorios').dataTable({
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

//sincroniza as ofetas
function sincronizarOfertas() {
    $("#sincronia_espera").modal("show");
    var idCurso = $("#idCurso").val();
    var idSemestre = $("#idSemestre").val();
    $.post(dir + "sincronizarOferta.php", {
            idCurso: idCurso,
            idSemestre: idSemestre
        },
        function (data, status) {
            retorno = JSON.parse(data);
            $("#sincronia_espera").modal("hide");
            if (retorno.status) {
                //mostra a mensagem de sucesso
                bootbox.alert(retorno.mensagem);
                listarOfertas();
            }
            else {
                //mostra mensagem de falha
                bootbox.alert(retorno.mensagem);
            }
        });
}

function deletarOferta(ident) {
    bootbox.confirm("Deseja realmente apagar esse regristro ?", function (result) {
        if (result) {
            $.post(dir + "deletarOferta.php", {
                    id: ident
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listarOfertas();
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

function copiarOferta(ident) {
    bootbox.confirm("Deseja realmente copiar essa oferta ?", function (result) {
        if (result) {
            $.post(dir + "copiarOferta.php", {
                    id: ident
                },
                function (data, status) {
                    console.log(data);
                    retorno = JSON.parse(data);
                    if (retorno.status) {
                        //mostra a mensagem de sucesso
                        bootbox.alert(retorno.mensagem);
                        listarOfertas();
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

//carrega as combos
function comboSala() {
    $('#sala').empty(); //remove all child nodes
    $.post("app/controller/sala/carregarCombo.php", {
            vazio: 0
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#sala").append(data);
        });
}

function comboTurno() {
    $('#turno').empty(); //remove all child nodes
    $('#turnolab').empty();
    $.post("app/controller/registro/carregarCombo.php", {
            identidade: 5,
            idpai: null
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#turno").append(data);
            //
            $("#turnolab").append('<option value="">Selecione o Turno</option>');
            $("#turnolab").append(data);
        });
}

function comboDia() {
    $('#diasemana').empty(); //remove all child nodes
    $('#dia').empty(); //remove all child nodes
    $.post("app/controller/registro/carregarCombo.php", {
            identidade: 6,
            idpai: null
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#diasemana").append(data);
            //
            $("#dia").append('<option value="">Selecione o Dia</option>');
            $("#dia").append(data);
        });
}

function comboLab() {
    $('#lab').empty(); //remove all child nodes
    $.post("app/controller/sala/carregarComboTipo.php", {
            tipo: 2
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#lab").append('<option value="">Selecione o Laborátorio</option>');
            $("#lab").append(data);
        });
}

function comboUso() {
    $('#tipouso').empty(); //remove all child nodes
    $.post("app/controller/registro/carregarCombo.php", {
            identidade: 7,
            idpai: null
        },
        function (data, status) {
            //Coloca os valores do retorno nos campos devidos
            $("#tipouso").append('<option value="">Selecione o Tipo de Uso</option>');
            $("#tipouso").append(data);
        });
}

$(document).ready(function () {
    // Lista os arquivos quando a pagina é carregada
    listarOfertas(); // chama a função
    listarAlocacoes();
    listarLaboratorios();

    comboSala();//carrega combos
    comboTurno();//carrega combos
    comboDia();
    comboLab();
    comboUso();

    $('#ofertas').dataTable();
    $('#oferta_modal').on('hide.bs.modal', function (event) {
        $('#oferta').bootstrapValidator("resetForm", true);
        $('#oferta').each(function () {
            this.reset();
            $("#id").val("");
        })
    });

    $('#laboratorios').dataTable();
    $('#laboratorio_modal').on('hide.bs.modal', function () {
        $('#laboratorio').bootstrapValidator("resetForm",true);
        $('#laboratorio').each(function () {
            this.reset();
            $("#idlab").val("");
        });
    });

});

//valida os campos do form
function validar() {
    //instancia um validador de campos
    $('#oferta').bootstrapValidator({
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
            },
            turno: {
                validators: {
                    notEmpty: {
                        message: 'O turno é requerido.'
                    }
                }
            },
            horario: {
                validators: {
                    notEmpty: {
                        message: 'O horário é requerido.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#oferta').data('bootstrapValidator').isValid();
}

//valida os campos do form
function validarLab() {
    //instancia um validador de campos
    $('#laboratorio').bootstrapValidator({
        message: 'Este valor não é válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            lab: {
                validators: {
                    notEmpty: {
                        message: 'O laborátorio é requerido.'
                    }
                }
            },
            tipouso: {
                validators: {
                    notEmpty: {
                        message: 'O tipo de uso deve ser especificado.'
                    }
                }
            },
            observacao: {
                validators: {
                    notEmpty: {
                        message: 'O observação é requerido.'
                    }
                }
            }
        }
    });
    //lança o retorno se é true (valido) ou false (invalido)
    return $('#laboratorio').data('bootstrapValidator').isValid();
}

function voltar_alocacao() {
    $("#main").load(dirview+"semestreLetivo.php");
}