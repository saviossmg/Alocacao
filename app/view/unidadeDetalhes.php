<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/unidadeDetalhe.js"></script>

<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Detalhes da Unidade: <?php echo $_POST['nomeunidadeparam'] ?></h3>
    <input type="hidden" id="idUnidade" name="idUnidade" value="<?php echo $_POST['idunidadeparam'] ?>">
    <div id="top" class="row">
        <nav class="nav">
            <a onclick="voltar_unidet()" class="btn btn-success btn-small" href="#">
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
                <a class="nav-link active" data-toggle="tab" href="#predios" role="tab">Predios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#cursos" role="tab">Cursos</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="predios" role="tabpanel">
                <nav class="nav">
                    <a class="btn btn-success btn-small" href="#" data-toggle="modal"
                       data-target="#unidade_predio_modal">
                        <i class="fa fa-plus-square"></i>Vincular Bloco <span class="glyphicon glyphicon-plus"
                                                                              aria-hidden="true"/>
                    </a>
                    <a class="nav-link disabled pull-right" href="#">Prédios e Blocos Vinculados a Unidade</a>
                </nav>
                <div class="records_content_prediosunidade"></div>
            </div>
            <div class="tab-pane" id="cursos" role="tabpanel">
                <div class="tab-pane active" id="cursos" role="tabpanel">
                    <nav class="nav">
                        <a class="btn btn-success btn-small" href="#" data-toggle="modal"
                           data-target="#unidade_curso_modal">
                            <i class="fa fa-plus-square"></i>Vincular Curso <span class="glyphicon glyphicon-plus"
                                                                                  aria-hidden="true"/>
                        </a>
                        <a class="nav-link disabled pull-right" href="#">Cursos Vinculados a Unidade</a>
                    </nav>
                    <div class="records_content_cursosunidade"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="unidade_predio_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Predios da Unidade</h4>
            </div>
            <div class="modal-body">
                <form id="predioUnidade" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Prédio</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="predio" id="predio">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvarPredio" onclick="vincularPredio();">
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

<div class="modal fade" id="unidade_curso_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Cursos da Unidade</h4>
            </div>
            <div class="modal-body">
                <form id="cursoUnidade" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Curso</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="curso" id="curso">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvarCurso" onclick="vincularCurso();">
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