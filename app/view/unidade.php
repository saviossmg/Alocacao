<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/unidade.js"></script>
<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Unidades e Campus.</h3>
    <div id="top" class="row">
        <nav class="nav">
            <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#unidade_modal">
                <i class="fa fa-plus-square"></i>Nova Unidade <span class="glyphicon glyphicon-plus"
                                                                    aria-hidden="true"/>
            </a>
            <a class="nav-link disabled pull-right" href="#">Unidades, Campus e outros Polos</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_unidades"></div>
    </div>
</div>
</div>
<!-- Content Section -->

<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="unidade_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Unidades</h4>
            </div>
            <div class="modal-body">
                <form id="unidade" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nome</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nome" id="nome"
                                   placeholder="Digite o nome da Unidade"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Endereço</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="endereco" id="endereco"
                                   placeholder="Digite o endereço da Unidade"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">CEP</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="cep" id="cep"
                                   placeholder="Digite o CEP da Unidade"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Latitude</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="latitude" id="latitude"
                                   placeholder="Digite a latitude da Unidade"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Longitude</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="longitude" id="longitude"
                                   placeholder="Digite a longitude da Unidade"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Diretor Geral</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="diretorgeral" id="diretorgeral">
                                <option value="">Escolha um(a) servidor(a)</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/app/controller/servidor/carregarCombo.php"?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Administrador</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="administrador"
                                    id="administrador">
                                <option value="">Escolha um(a) servidor(a)</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/app/controller/servidor/carregarCombo.php"?>
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
