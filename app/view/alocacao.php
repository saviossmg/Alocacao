<?php
$idsemestreletivo = 0;
$curso;
$idCurso = 0;
$semestre;
$idSemestre = 0;
if (!empty($_POST)) {
    $idsemestreletivo = $_POST['idsemestreletivo'];
    $curso = $_POST['curso'];
    $idCurso = $_POST['idcurso'];
    $semestre = $_POST['semestre'];
    $idSemestre = $_POST['idsemestre'];
}
?>
<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/alocacao.js"></script>

<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Alocação - Curso: <?php echo $curso ?>, Semestre: <?php echo $semestre ?>. </h3>
    <input type="hidden" id="idSemestreletivo" name="idSemestreletivo" value="<?php echo $_POST['idsemestreletivo'] ?>">
    <input type="hidden" id="idCurso" name="idCurso" value="<?php echo $idCurso ?>">
    <input type="hidden" id="idSemestre" name="idSemestre" value="<?php echo $idSemestre ?>">
    <div id="top" class="row">
        <nav class="nav">
            <a onclick="voltar_alocacao()" class="btn btn-success btn-small" href="#">
                <i class="fa fa-plus-square"></i>Voltar <span class="glyphicon glyphicon-arrow-left"
                                                              aria-hidden="true"/>
            </a>
        </nav>
    </div>
    <!-- Content Section-->
    <div class="row">
        <!-- Nav tabs-->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#salas" role="tab">Ofertas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aloc" role="tab">Alocações</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#laborato" role="tab">Laborátorios</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="salas" role="tabpanel">
                <nav class="nav">
                    <button type="button" class="btn btn-success" id="salvarCurso"
                            onclick="sincronizarOfertas();">
                        Sincrozinar <span class="glyphicon glyphicon-repeat" aria-hidden="true"/>
                    </button>
                    <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#alocoferta_info">
                        Ofertas disponibilizadas para o curso no semestre letivo selecionado. (?)</a>
                </nav>
                <div class="records_content_ofertas"></div>
            </div>
            <div class="tab-pane" id="aloc" role="tabpanel">
                <nav class="nav">
                    <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#alocaloc_info">
                        Salas alocadas para o semestre letivo do curso selecionado. (?)</a>
                </nav>
                <div class="records_content_alocacoes"></div>
            </div>
            <div class="tab-pane" id="laborato" role="tabpanel">
                <nav class="nav">
                    <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#laboratorio_modal">
                        <i class="fa fa-plus-square"></i>Alocar Laborátorio <span class="glyphicon glyphicon-plus" aria-hidden="true"/>
                    </a>
                    <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#aloclab_info">
                        Personalização do uso dos laborátorios que não estão na alocação convencional. (?)</a>
                </nav>
                <div class="records_content_laboratorio"></div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modals -->
<div class="modal fade" id="oferta_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Alocar Oferta</h4>
            </div>
            <div class="modal-body">
                <form id="oferta" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="oferta" name="oferta">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Semestre</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="semestre" id="semestre" disabled/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Disciplina</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="disciplina" id="disciplina" disabled/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Dia</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="diasemana" id="diasemana">
                                <option value="">Selecione um dia</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Turno</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="turno" id="turno">
                                <option value="">Selecione o turno</option>
                                <?php include "app/controller/registro/carregarCombo.php" ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Horário</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="horario" id="horario">
                                <option value=1>Turno Completo</option>
                                <option value=2>Horário 1</option>
                                <option value=3>Horário 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Sala</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="sala" id="sala">
                                <option value=""></option>
                                <?php include "app/controller/sala/carregarCombo.php" ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvar" onclick="alocarOferta();">
                                Salvar
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"/></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"/></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Info -->
<div class="modal fade" id="sincronia_espera" tabindex="-2" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Aguarde a Sincronização</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-7 text-center">
                            <img src="https://www.drupal.org/files/issues/ajax-loader.gif" class="center-block"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="laboratorio_modal" tabindex="-3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Laborátorios</h4>
            </div>
            <div class="modal-body">
                <form id="laboratorio" method="post" class="form-horizontal">
                    <input type="hidden" id="idlab" name="idlab">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Laborátorio</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="lab" id="lab"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tipo de Uso</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="tipouso" id="tipouso"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Turno</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="turnolab" id="turnolab"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Dia</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="dia" id="dia"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Curso</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="curso" id="curso"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Observação</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="observacao" id="observacao"
                                   placeholder="Digite a Observação dessa exceção."/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvarlab" onclick="salvarLaboratorio();">
                                Salvar
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"/></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"/></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Info -->
<div class="modal fade" id="alocoferta_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alocação - Curso: Ofertas - Ajuda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo acessar a Alocação. Esta aba mostra as Ofertas VINCULADAS e SINCRONIZADAS
                    ao Curso e Semestre selecionado, sem estarem alocadas em uma sala. É possível sincronizar e excluir uma Oferta da Unidade. <br><br>
                    Todas as Ofertas são sincronizadas diretamente do Sistema -- da Unitins, podendo essas ofertas serem sincronizadas e atualizadas
                    mesmo que a oferta já tenha sido alocada.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Info -->
<div class="modal fade" id="alocaloc_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alocação - Curso: Alocações - Ajuda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo acessar a Alocação. Esta aba mostra as Ofertas ALOCADAS
                    ao Curso e Semestre selecionado, mostrando a sala que foi designada. É possível editar e excluir uma Alocação. <br><br>
                    Todas as Ofertas são sincronizadas diretamente do Sistema -- da Unitins, podendo essas ofertas serem sincronizadas e atualizadas
                    mesmo que a oferta já tenha sido alocada.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Info -->
<div class="modal fade" id="aloclab_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alocação - Curso: Alocações - Ajuda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo acessar a Alocação. Esta aba mostra os usos ESPECIFICOS de Laboratório ALOCADOS
                    ao Semestre selecionado, mostrando o tipo de uso. É possível editar e excluir um uso Alocação. <br>
                    Esses usos não fazem parte do sistema da Unitins e devem ser inseridos pelo Administrador.<br><br>
                    Esses são os tipos e os parâmetros obrigatórios para cada um deles:<br>
                    <ul>
                        <li>Monitoria: Dia, Curso e Turno, restrição pequena de uso (trava o dia e o turno);</li>
                        <li>Uso Comum: Turno, restrição média de uso (trava todo o turno) ;</li>
                        <li>Uso Exclusivo: Curso, restrição alta (trava apenas par ao curso selecionado); <li>
                    </ul><br>
                    É possível utilizar mais de um tipo no mesmo laboratório, desde que se respeite as regras de inserção.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>