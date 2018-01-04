<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/salaDetalhe.js"></script>

<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Detalhes da Sala: <?php echo $_POST['nomesalaparam'] ?></h3>
    <input type="hidden" id="idSala" name="idSala" value="<?php echo $_POST['idsalaparam'] ?>">
    <div id="top" class="row">
        <nav class="nav">
            <a onclick="voltar_saldet()" class="btn btn-success btn-small" href="#">
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
                <a class="nav-link active" data-toggle="tab" href="#salas" role="tab">Recursos da Sala</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="salas" role="tabpanel">
                <nav class="nav">
                    <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#sala_recurso_modal">
                        <i class="fa fa-plus-square"></i>Adicionar Recurso <span class="glyphicon glyphicon-plus"
                                                                                 aria-hidden="true"/>
                    </a>
                    <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#saladetalhes_info">
                        Recursos Presentes na Sala. (?)</a>
                </nav>
                <div class="records_content_recursossala"></div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="sala_recurso_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Recursos da Sala</h4>
            </div>
            <div class="modal-body">
                <form id="recursoSala" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id"/>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Recurso</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="recurso" id="recurso">
                                <option value="">Selecione o Equipamento.</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Quantidade</label>
                        <div class="col-md-7">
                            <input type="number" class="form-control" name="quantidade" id="quantidade"
                                   placeholder="Digite a quantidade de equipamentos existentes"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvarRecurso"
                                    onclick="inserirRecurso();">
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

<!-- Modal - Info -->
<div class="modal fade" id="saladetalhes_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Sala: Recursos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo acessar os detalhas de uma Sala. Esta aba mostra os Recursos VINCULADAS
                    ao Prédio selecionado. É possível vincular e desvincular uma recursos. <br><br>
                    Para vincular um recurso sala, é necessário:<br>
                    <ul>
                        <li>Selecionar um Recurso que esteja ativo;</li>
                        <li>A quantidade presente;</li>
                    </ul><br>
                    Só aparece para vincular os Recursos que estão Ativos.
                    </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>