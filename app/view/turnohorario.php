<!-- Custom JS file -->
<script type="text/javascript" src="app/view/js/turnohorario.js"></script>
<!-- Content Section -->
<div class="container-fluid">
    <h3 class="page-header"><br/>Turnos/Horários.</h3>
    <div id="top" class="row">
        <nav class="nav">
            <a class="btn btn-success btn-small" href="#" data-toggle="modal" data-target="#turnohorario_modal">
                <i class="fa fa-plus-square"></i>Novo Horário <span class="glyphicon glyphicon-plus"
                                                                    aria-hidden="true"/>
            </a>
            <a class="nav-link disabled pull-right" href="#">Horários de iniicio e fim dos turnos.</a>
        </nav>
    </div>
    <div class="row">
        <div class="records_content_turnoshorarios"></div>
    </div>
</div>
</div>
<!-- Content Section -->

<!-- Bootstrap Modals -->
<!-- Modal - Adicionar novo registro -->
<div class="modal fade" id="turnohorario_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Gerenciamento de Turnos/Horários</h4>
            </div>
            <div class="modal-body">
                <form id="turnohorario" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Turno</label>
                        <div class="col-md-7 selectContainer">
                            <select class="form-control" class="form-control" name="turno" id="turno">
                                <?php include "app/controller/registro/carregarCombo.php" ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Horário 1: Início</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="horaainicio" id="horaainicio"
                                   placeholder="HH:MM"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Horário 1: Final</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="horaafim" id="horaafim"
                                   placeholder="HH:MM"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Horário 2: Início</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="horabinicio" id="horabinicio"
                                   placeholder="HH:MM"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Horário 2: Final</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="horabfim" id="horabfim"
                                   placeholder="HH:MM"/>
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