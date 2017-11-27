<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/cursoDetalhe.js"></script>

<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Detalhes do Curso: <?php echo $_POST['nomecursoparam'] ?></h3>
    <input type="hidden" id="idCurso" name="idCurso" value="<?php echo $_POST['idcursoparam'] ?>">
    <div id="top" class="row">
        <nav class="nav">
            <a onclick="voltar_curdet()" class="btn btn-success btn-small" href="#">
                <i class="fa fa-plus-square"></i>Voltar <span class="glyphicon glyphicon-arrow-left"
                                                              aria-hidden="true"/>
            </a>
        </nav>
    </div>
    <!-- Content Section -->
    <div class="row">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#salas" role="tab">Disciplinas do Curso</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="salas" role="tabpanel">
                <nav class="nav">
                    <a class="btn btn-success btn-small" href="#" data-toggle="modal"
                       data-target="#curso_disciplina_modal">
                        <i class="fa fa-plus-square"></i>Vincular Disciplina <span class="glyphicon glyphicon-plus"
                                                                                   aria-hidden="true"/>
                    </a>
                    <a class="nav-link disabled pull-right" href="#">Disciplinas do Curso.</a>
                </nav>
                <div class="records_content_disciplinascurso"></div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="curso_disciplina_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Disciplinas de Curso</h4>
            </div>
            <div class="modal-body">
                <form id="cursoDisciplina" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Disciplina</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="disciplina" id="disciplina">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvarDisciplina"
                                    onclick="vincularDisciplina();">
                                Vincular
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
