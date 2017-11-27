<?php
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
$html = '
<style type="text/css">
    @page {	margin: 40px; }
    p.cabecalho { font-size: 11px; font-family: Calibri; }
    p.texto1 { font-size: 44px; font-weight: bold; font-family: Calibri; }
    p.texto2 { font-size: 30px;	font-weight: bold; font-family: Calibri; }
    p.texto3 { font-size: 30px;	font-weight: bold; font-family: Calibri; }
    p.texto3a { font-size: 22px; font-family: Calibri; }
    p.rodape { font-size: 22px;	font-weight: bold;	font-family: Calibri; text-align: center;}
</style>
<div style="border: 5; border-style: double;height: 1122px; width: 793px;">
    <div id="is_cab">
        <table id="cabecalho" style="margin-left: auto; margin-right: auto;">
            <tbody>
            <tr><br></tr>
            <tr>
                <td><center><img src="' . BASE_DIR . 'img/unitins_logo_imp.png"></center></td>
            </tr>
            <tr>
                <td>
                    <p class="cabecalho"><center>
                        FUNDAÇÃO UNIVERSIDADE DO TOCANTINS – UNITINS<br>
                        PRÓ-REITORIA DE GRADUAÇÃO<br>
                        CÂMPUS GRACIOSA
                    </center></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div id="is_nomesala">
        <table id="nomesala" style="margin-left: auto; margin-right: auto;">
            <tbody>
            <tr>
                <td>
                    <p class="texto1"><center>SALA</center></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div id="is_content">
        <table id="content" border="1" style="margin-left: auto; margin-right: auto; border-style: double;" >
            <tbody>
            <tr>
                <td width="340px" height="90px" bgcolor="#c0c0c0"><p class="texto2"><center>TURNO</center></p></td>
                <td width="340px" height="90px" bgcolor="#c0c0c0"><p class="texto2"><center>CURSO/PERÍODO</center></p></td>
            </tr>
            <tr>
                <td width="340px" height="90px"><p class="texto2"><center>Manhã (2ª à 6ª)</center></p></td>
                <td width="340px" height="90px"><center> - </center></td>
            </tr>

            <tr>
                <td width="340px" height="90px"><p class="texto2"><center>Tarde (2ª à 6ª)</center></p></td>
                <td width="340px" height="90px"><center> x </center></td>
            </tr>
            <tr>
                <td width="340px" height="90px"><p class="texto2"><center>Noite (2ª à 6ª)</center></p></td>
                <td width="340px" height="90px"><center> v </center></td>
            </tr>
            <tr>
                <td width="340px" height="90px"><p class="texto2"><center>Manhã (Sáb)</center></p></td>
                <td width="340px" height="90px"><center> t </center></td>
            </tr>
            
            </tbody>
        </table>
    </div>
    <div id="is_bottom1">
        <table id="bottom1" style="margin-left: auto; margin-right: auto;">
            <tbody>
            <tr>
                <td>
                    <p class="texto3"><center>CONSULTAR TABELA DE AGENDAMENTO</center></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <p class="rodape" style="position: bottom;">CURSOS PRESENCIAIS – UNITINS</p>
</div>
';

echo $html;