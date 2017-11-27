/*
 * Criado por: Sávio Martins Valentim
 * Data: 07/11/2017
 */

var dirview = "app/view/";

$(document).ready(function () {
    //carrega a primeira pagina
    $("#main").load(dirview+"principal.php");
    //primeira parte do menu
    $("#principal_nav").on("click", function(){$("#main").load(dirview+"principal.php");});
    $("#unidade_nav").on("click", function(){$("#main").load(dirview+"unidade.php");});
    $("#predio_nav").on("click", function(){$("#main").load(dirview+"predio.php");});
    $("#alocacao_nav").on("click", function(){$("#main").load(dirview+"semestreLetivo.php");});
    $("#relatorio_nav").on("click", function(){$("#main").load(dirview+"relatorio.php");});

    //dropdown consultas
    $("#semestre_nav").on("click", function(){$("#main").load(dirview+"semestre.php");});
    $("#curso_nav").on("click", function(){$("#main").load(dirview+"curso.php");});
    $("#sala_nav").on("click", function(){$("#main").load(dirview+"sala.php");});
    $("#equipamento_nav").on("click", function(){$("#main").load(dirview+"equipamento.php");});
    $("#servidor_nav").on("click", function(){$("#main").load(dirview+"servidor.php");});
    $("#turnohorario_nav").on("click", function(){$("#main").load(dirview+"turnohorario.php");});

    //dropdown registros
    $("#status_nav").on("click", function(){$("#main").load(dirview+"status.php");});
    $("#tipoequipamentos_nav").on("click", function(){$("#main").load(dirview+"tipoequipamento.php");});
    $("#tipossalas_nav").on("click", function(){$("#main").load(dirview+"tiposSala.php");});
    $("#prioridade_nav").on("click", function(){$("#main").load(dirview+"prioridade.php");});

});
