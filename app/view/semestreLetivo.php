<?php
$idsemestre = 0;
if (!empty($_POST)) {
    $idsemestre = $_POST["idsemestreparam"];
    $descsemestreparam = $_POST["descsemestreparam"];
}
?>

<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/semestreLetivo.js"></script>
<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Alocação - Semestre letivo. <?php if(!empty($_POST["descsemestreparam"])){ echo $descsemestreparam ;}?></h3>
    <input type="hidden" id="idSemestre" name="idSemestre" value="<?php echo $idsemestre ?>">
    <div id="top" class="row">
        <nav class="nav">
            <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#semestreletivo_modal">
                <i class="fa fa-plus-square"></i>Novo Semestre Letivo <span class="glyphicon glyphicon-plus"
                                                                            aria-hidden="true"/>
            </a>
            <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#semestreletivo_info">Semestre
                letivo cadastrado por curso para a alocação(?)</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_semestresletivos"></div>
    </div>
</div>
<!-- Content Section -->

<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="semestreletivo_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Semestre Letivo</h4>
            </div>
            <div class="modal-body">
                <form id="semestreletivo" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Semestre</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="semestre" id="semestre">
                                <option value="">Escolha o Semestre</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/app/controller/semestre/carregarCombo.php" ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Curso</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control selectpicker" name="curso" id="curso"
                                    data-live-search="true" data-header="Selecione o(s) curso(s)."
                                    multiple data-selected-text-format="count" data-actions-box="true">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvar" onclick="salvarRegistro();">
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
<div class="modal fade" id="semestreletivo_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alocação: Semestre Letivo - Ajuda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo o cadastro dos Semestres Letivos. É possível
                    cadastrar e excluir um Semestre Letivo, acessando os detalhes sobre ele. Um semestre letivo é necessário para de fazer
                    uma alocação, sendo cada semestre letivo separado por curso e vinculado a um Semestre previamente cadastrado. <br><br>
                    Para cadastrar um Semestre Letivo, é necessário:<br>
                    <ul>
                        <li>O Semestre;</li>
                        <li>Os cursos;</li>
                    </ul><br>
                    Todos os campos são obrigatórios, e há a possibilidade de selecionar vários cursos, sendo obrigatório ao menos um. Não é
                    possível cadastrar um semestre letivo para um curso que já está com cadastro. <br><br>
                    Diferente do Semestre, o Semestre Letivo está vinculado a um Curso e um Semestre previamente cadastrado.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>