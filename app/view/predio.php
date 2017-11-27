<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/predio.js"></script>
<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Predios e Blocos.</h3>
    <div id="top" class="row">
        <nav class="nav">
            <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#predio_modal">
                <i class="fa fa-plus-square"></i>Novo Prédio <span class="glyphicon glyphicon-plus" aria-hidden="true"/>
            </a>
            <a class="nav-link disabled pull-right" href="#">Predios e Blocos</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_predios"></div>
    </div>
</div>
</div>
<!-- Content Section -->

<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="predio_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Predios</h4>
            </div>
            <div class="modal-body">
                <form id="predio" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id"/>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nome</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nome" id="nome"
                                   placeholder="Digite o nome do prédio"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Pisos</label>
                        <div class="col-md-7">
                            <input type="number" class="form-control" name="pisos" id="pisos"
                                   placeholder="Digite a quantidade de pisos"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Unidade</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="unidade" id="unidade">
                                <option value="0">Escolha uma Unidade (campus)</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/alocacao/app/controller/unidade/carregarCombo.php" ?>
                            </select>
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
