<?php include 'top.php' ?>

    <!-- Custom JS file -->
    <script type="text/javascript" src="app/view/professor.js"></script>
    <!-- Content Section -->
    <div class="container-fluid">
        <h3 class="page-header"><br/>Professores da Universidade.</h3>
        <div id="top" class="row">
            <nav class="nav">
                <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#professor_modal">
                    <i class="fa fa-plus-square"></i>Novo Professor <span class="glyphicon glyphicon-plus"aria-hidden="true"/>
                </a>
                <a class="nav-link disabled pull-right" href="#">Servidores docentes da universidad</a>
            </nav>
        </div>
        <div class="row">
            <div class="records_content_professores"></div>
        </div>
    </div>
    <!-- Content Section -->

    <!-- Bootstrap Modals -->
    <!-- Modal - Adicionar novo registro -->
    <div class="modal fade" id="professor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Gerenciamento de Servidores</h4>
                </div>
                <div class="modal-body">
                    <form id="professor" method="post" class="form-horizontal">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nome</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="nome" id="nome"
                                       placeholder="Digite o nome do Professor"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Matricula</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="matricula" id="matricula"
                                       placeholder="Digite a matricula do Professor"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Cargo</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="cargo" id="cargo"
                                       placeholder="Digite o Cargo do Professor"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ativo</label>
                            <div class="col-md-7 selectContainer">
                                <select class="form-control" class="form-control" name="ativo" id="ativo">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
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