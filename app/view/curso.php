<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/curso.js"></script>
<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Cursos.</h3>
    <div id="top" class="row">
        <nav class="nav">
            <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#curso_modal">
                <i class="fa fa-plus-square"></i>Novo Curso <span class="glyphicon glyphicon-plus" aria-hidden="true"/>
            </a>
            <a class="nav-link disabled pull-right" href="#">Cursos da Universidade</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_cursos"></div>
    </div>
</div>

<!-- Content Section -->

<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="curso_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Cursos</h4>
            </div>
            <div class="modal-body">
                <form id="curso" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nome</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nome" id="nome"
                                   placeholder="Digite o nome do Curso."/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Sigla</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="sigla" id="sigla"
                                   placeholder="Digite uma sigla para o Curso."/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Código</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="codigo" id="codigo"
                                   placeholder="Digite o Codigo do Curso"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Unidade</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="unidade" id="unidade">
                                <option value="">Selecione uma unidade</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/alocacao/app/controller/unidade/carregarCombo.php" ?>
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