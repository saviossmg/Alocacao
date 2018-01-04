<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Alocação de Salas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="css/bootstrapValidator.min.css" rel="stylesheet">
    <link href="css/formato.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker.standalone.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">

    <link href="css/layout.css" rel="stylesheet">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.mask.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootbox.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/bootstrapValidator.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>

    <script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datepicker.pt-BR.min.js"></script>

    <script type="text/javascript" src="app/view/js/index.js"></script>
</head>
<body>
<!-- NavBar Section -->
<nav class="navbar navbar-inverse  navbar-default navbar-fixed-top " >
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">UNITINS - Alocação de Salas</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="#" id="principal_nav">Home</a></li>
            <li><a href="#" id="unidade_nav">Unidades</a></li>
            <li><a href="#" id="predio_nav">Prédios</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Operações
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#" id="alocacao_nav">Alocação</a></li>
                    <li><a href="#" id="relatorio_nav">Relatórios</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Consultas
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#" id="semestre_nav">Semestres</a></li>
                    <li><a href="#" id="curso_nav">Cursos</a></li>
                    <li><a href=#" id="sala_nav">Salas</a></li>
                    <li><a href="#" id="equipamento_nav">Equipamentos</a></li>
                    <li><a href="#" id="servidor_nav">Servidores</a></li>
                    <li><a href="#" id="turnohorario_nav">Turnos/Horários</a></li>

                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tipos
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
<!--                    <li><a href="#" id="status_nav">Status</a></li>-->
                    <li><a href="#" id="tipoequipamentos_nav">Equipamentos</a></li>
                    <li><a href="#" id="tipossalas_nav">Salas</a></li>
<!--                    <li><a href="#" id="prioridade_nav">Prioridade</a></li>-->
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#" id="sair/-nav"><span class="glyphicon glyphicon-log-in"></span>Sair</a></li>
        </ul>
    </div>
</nav>
<!-- NavBar Section -->
<!-- Content Section -->
<div id="main" class="indexmain container-fluid pre-scrollable " style="max-height: 82vh">
    <!---->

    <!---->
</div>
<!-- Content Section -->
<!-- Bottom Section -->
<div class="indexrodape footer navbar-fixed-bottom" style="max-height: 13vh">
    <div class='col-xs-12 col-sm-2 placeholder'>
        <div class="logo_footer"></div>
    </div>
    <div class='col-xs-12 col-sm-10 placeholder'>
        <div class="disciplina">
            Desenvolvido por Sávio Martins Valentim, Acadêmico de Sistemas da Informação - 2017
        </div>
    </div>
</div>
</body>
</html>