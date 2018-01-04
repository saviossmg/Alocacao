<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/predioDetalhe.js"></script>

<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Detalhes do Prédio: <?php echo $_POST['nomepredioparam'] ?></h3>
    <input type="hidden" id="idPredio" name="idPredio" value="<?php echo $_POST['idpredioparam'] ?>">
    <div id="top" class="row">
        <nav class="nav">
            <a onclick="voltar_predet()" class="btn btn-success btn-small" href="#">
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
                <a class="nav-link active" data-toggle="tab" href="#salas" role="tab">Salas</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="salas" role="tabpanel">
                <nav class="nav">
                    <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#predio_sala_modal">
                        <i class="fa fa-plus-square"></i>Vincular Sala <span class="glyphicon glyphicon-plus"
                                                                             aria-hidden="true"/>
                    </a>
                    <a class="nav-link disabled pull-right" data-toggle="modal" data-target="#predetalhes_info">
                        Salas vinculadas ao Prédio. (?)</a>
                </nav>
                <div class="records_content_salaspredio"></div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="predio_sala_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Salas do Prédio</h4>
            </div>
            <div class="modal-body">
                <form id="salaPredio" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Sala</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="sala" id="sala">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="salvarSala" onclick="vincularSala();">
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
<div class="modal fade" id="predetalhes_info" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Prédio: Salas </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p align="justify">
                    Este menu do sistema tem como objetivo acessar os detalhas de um Prédio. Esta aba mostra as Salas VINCULADAS
                    ao Prédio selecionado. É possível vincular e desvincular uma sala do Prédio,  além de acessar os detalhes da sala. <br><br>
                    Para vincular uma sala, é necessário:<br>
                    <ul>
                        <li>Selecionar uma Sala que esteja livre;</li>
                    </ul><br>
                    Só aparece para vincular as Salas que estão livres.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>