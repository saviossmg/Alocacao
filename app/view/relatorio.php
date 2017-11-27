<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/relatorio.js"></script>

<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Relatórios.</h3>
    <div id="top" class="row">
        <nav class="nav">
            <a class="nav-link disabled pull-right" href="#">Relatórios das Alocações.</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_relatorios" id="rts"></div>
    </div>
</div>
<!-- Content Section -->

<!-- Bootstrap Modals -->
<div class="modal fade" id="rel1_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Relatório Identificação de Salas</h4>
            </div>
            <div class="modal-body">
                <form id="relatorio1" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Semestre</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="semestre" id="semestre">
                                <option value="">Selecione o Semestre</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/alocacao/app/controller/semestre/carregarCombo.php" ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Campus</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="unidade" id="unidade"
                                    onchange="mudaCampus()">
                                <option value="">Selecione o Campus</option>
                                <?php include $_SERVER['DOCUMENT_ROOT']."/alocacao/app/controller/unidade/carregarCombo.php" ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Salas</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control selectpicker" name="salas" id="salas"
                                    data-live-search="true" data-header="Selecione a(s) sala(s)."
                                    multiple data-selected-text-format="count" data-actions-box="true">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-3">
                            <button type="button" class="btn btn-primary" id="print" onclick="impressao1();">
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
<div class="modal fade" id="impressao_espera" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Aguarde a Impressão</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-7 text-center">
                            <img src="https://www.drupal.org/files/issues/ajax-loader.gif" class="center-block"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


