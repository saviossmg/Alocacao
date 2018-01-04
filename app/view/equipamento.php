<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/equipamento.js"></script>
<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Equipamentos.</h3>
    <div id="top" class="row">
        <nav class="nav">
            <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#equipamento_modal">
                <i class="fa fa-plus-square"></i>Novo Equipamento <span class="glyphicon glyphicon-plus"
                                                                        aria-hidden="true"/>
            </a>
            <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#equipamento_info">Equipamentos presentes nas Salas e Laboratorios (?)</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_equipamentos"></div>
    </div>
</div>
</div>
<!-- Content Section -->

<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="equipamento_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Equipamentos</h4>
            </div>
            <div class="modal-body">
                <form id="equipamento" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nome</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="descricao" id="descricao"
                                   placeholder="Digite o nome do equipamento."/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tipo</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="tipoequipamento"
                                    id="tipoequipamento">
                                <option value="">Selecione o tipo de equipamento</option>
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

<!-- Modal - Info -->
<div class="modal fade" id="equipamento_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Equipamentos - Ajuda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo o cadastro e manipulação dos Equipamentos da Universidade. É possível
                    cadastrar, editar e excluir um Equipamento, diferente do Classes de Equipamentos, aqui é feito o cadastro de um equipamento
                    em especifico. <br><br>Para cadastrar um novo Equipamento, é necessário:<br>
                    <ul>
                        <li>Nome;</li>
                        <li>Tipo do Equipamento;</li>
                        <li>Se está Ativa ou Não;</li>
                    </ul><br>
                    Todos os campos são obrigatórios.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>