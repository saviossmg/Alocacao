<?php include 'top.php';?>
    <!-- Custom JS file -->
    <script type="text/javascript" src="app/view/disciplina.js"></script>
    <!-- Content Section -->
    <div class="container-fluid">
        <h3 class="page-header"><br/>Disciplinas.</h3>
        <div id="top" class="row">
            <nav class="nav">
                <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#disciplina_modal">
                    <i class="fa fa-plus-square"></i>Nova Disciplina <span class="glyphicon glyphicon-plus"aria-hidden="true"/>
                </a>
                <a class="nav-link disabled pull-right" href="#">Disciplinas da Universidade</a>
            </nav>
        </div>
        <div class="row">
            <div class="records_content_disciplinas"></div>
        </div>
    </div>
    </div>
    <!-- Content Section -->

    <!-- Bootstrap Modals -->
    <!-- Modal - Adicionar novo registro -->
    <div class="modal fade" id="disciplina_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Gerenciamento de Disciplinas</h4>
                </div>
                <div class="modal-body">
                    <form id="disciplina" method="post" class="form-horizontal">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nome</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="descricao" id="descricao"
                                       placeholder="Digite o nome da Disciplina."/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Código Curricular</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="codcurricular" id="codcurricular"
                                       placeholder="Digite o Codigo Curricular"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Período</label>
                            <div class="col-md-7">
                                <input type="number" class="form-control" name="periodo" id="periodo"
                                       placeholder="Digite o periodo da Disciplina"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Curso</label>
                            <div class="col-md-7 selectContainer">
                                <select class="form-control" class="form-control" name="curso" id="curso">
                                    <option value=""></option>
                                    <?php include "app/controller/curso/carregarCombo.php" ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Pré-Requísito</label>
                            <div class="col-md-7 selectContainer">
                                <select class="form-control" class="form-control" name="prerequisito" id="prerequisito">
                                    <option value="0">Nenhuma Disciplina</option>
                                    <?php include "app/controller/disciplina/carregarCombo.php" ?>
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
<?php include 'bottom.php' ?>