<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 04/11/2017
 * Arquivo responsavel por imprimir o relatorio de porta de salas
 */
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
require_once BASE_DIR . 'mpdf/mpdf.php';

$arquivo = "impressao/";

//variavel para mostrar o resultado final
$resultado = [];
$mensagem = "";

try {
    //parametros capturados via post
    $salas = $_POST['salas'];

    //validação dos campos
    if (empty($salas) || empty($_POST['semestre']) || empty($_POST['campus'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($salas)) {
            $mensagem .= "1 - Salas <br>";
        }
        if (empty($_POST['semestre'])) {
            $mensagem .= "2 - Semestre <br>";
        }
        if (empty($_POST['campus'])) {
            $mensagem .= "3 - Campus <br>";
        }
        throw new Exception($mensagem);
    }

    $mpdf = new mPDF();
    $htmlx = "";

    //contadores
    $total = count($salas);
    $i = 1;

    $semestre = $entityManager->find('Vwsemestre', $_POST['semestre']);
    $campus = $entityManager->find('Unidade', $_POST['campus']);
	
    //perccore as salas para preencher o HTML
    foreach ($salas as $idx => $val) {
        $model = $entityManager->find('Sala', $val);
        //
        $qb = $entityManager->createQueryBuilder();
        $qb->select("Als, Sa, O, Sl, S")
            ->from('Alocacaosala', "Als")
            ->innerJoin("Als.sala", "Sa")
            ->innerJoin("Als.oferta", "O")
            ->innerJoin("Als.semestre", "Sl")
            ->innerJoin("Sl.semestre", "S")
            ->where('S.id = :semestre')
            ->andWhere('Sa.id = :sala')
            ->andWhere('Als.id IS NOT NULL')
            ->setParameter('semestre', $semestre->getId())
            ->setParameter('sala', $model->getId());
        $rs = $qb->getQuery()->getResult();

        $ret = '';
        $tipoSala = $model->getTipo()->getId();

		if ($tipoSala == 1) {
            $ret = preencherSala($model,$rs,$entityManager);
        }
        if ($tipoSala == 2){
            //procura se tem registros de laboratorios especificos
            $qb = $entityManager->createQueryBuilder();
            $qb->select("l, S, La, Tu, D")
                ->from('Laboratorio', "l")
                ->innerJoin("l.semestre", "S")
                ->innerJoin("l.laboratorio", "La")
                ->innerJoin("l.tipouso", "Tu")
                ->innerJoin("l.dia", "D")
                ->where('S.id = :semestre')
                ->andWhere('La.id = :lab')
                ->setParameter('semestre', $semestre)
                ->setParameter('lab', $model->getId());
            $rt = $qb->getQuery()->getResult();
            $ret = preencherLabim($model,$rs,$rt);
        }
        if($tipoSala == 22){
		    //sala de orietanção
            $ret = prencherOrientacao($model,$rs);
        }

        if ($i <= $total) {
            $i++;
            $htmlx .= $ret;
        }
    }

    $nomesem = str_replace("/", ".", $semestre->getDescricao());
    $arquivo .= "IDENTIFICACAO_SALAS-".$campus->getNome()."-".$nomesem.".pdf";

    $mpdf->WriteHTML($htmlx);
    $filename = BASE_DIR . $arquivo;
    $mpdf->Output($filename, 'F');

    $mensagem = "Arquivos impresso com sucesso!";
    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $arquivo];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

//HTML QUE ESTARA NO ARQUIVO
function preencherSala($model, $salasalocadas, $eM)
{
    $campus = strtoupper($model->getPredio()->getUnidade()->getNome());
    $bloco = strtoupper($model->getPredio()->getNome());
    $vazio = '<p class="texto3">---</p>';
    //varivaies de controle
    $manha = $vazio; //id = 9
    $tarde = $vazio; //id = 10
    $noite = $vazio; //id = 11
    $sabado = $vazio; //segunda a sextta id = 13 a 17 e sab id = 18
    $sabtar = $vazio; //segunda a sextta id = 13 a 17 e sab id = 18
    //
    $cMan = 0;
    $cTar = 0;
    $cNoi = 0;
    //
    $dMan = [];
    $dTar = [];
    $dNoi = [];
    //
    $pMan = [];
    $pTar = [];
    $pNoi = [];
    $pSab = "";
    $pSabtar = null;
    //
    $cursoMan = null;
    $cursoTar = null;
    $cursoNoi = null;
    $cursoSab = null;
    $cursoSabtarde = null;
    //
    $disciMan = [];
    $disciTar = [];
    $disciNoi = [];
    $disciSab = null;
    $disciSabtarde = null;

    if (count($salasalocadas) > 0) {
        //se o contador nao estiver vazio então ele precisa verificar as salas
        //verifica os horarios
        foreach ($salasalocadas as $idx => $val) {
            $turno = $val->getOferta()->getTurno()->getId();
            $dia = $val->getOferta()->getDiasemana()->getId();
            //MANHA
            if ($turno == 9 && $dia != 18) {
                $dMan[$cMan]["id"] = $val->getOferta()->getDiasemana()->getId();
                $dMan[$cMan]["desc"] = $val->getOferta()->getDiasemana()->getDescricao();
                $pMan[$cMan] = $val->getOferta()->getPeriodo();
                $disciMan[$cMan] = $val->getOferta()->getDisciplina();
                $cursoMan = $val->getOferta()->getCurso()->getSigla();
                $cMan++;
            }
            //TARDE
            if ($turno == 10 && $dia != 18) {
                $dTar[$cTar]["id"] = $val->getOferta()->getDiasemana()->getId();
                $dTar[$cTar]["desc"] = $val->getOferta()->getDiasemana()->getDescricao();
                $pTar[$cTar] = $val->getOferta()->getPeriodo();
                $disciTar[$cTar] = $val->getOferta()->getDisciplina();
                $cursoTar = $val->getOferta()->getCurso()->getSigla();
                $cTar++;
            }
            //NOITE
            if ($turno == 11 && $dia != 18) {
                $dNoi[$cNoi]["id"] = $val->getOferta()->getDiasemana()->getId();
                $dNoi[$cNoi]["desc"] = $val->getOferta()->getDiasemana()->getDescricao();
                $pNoi[$cNoi] = $val->getOferta()->getPeriodo();
                $disciNoi[$cNoi] = $val->getOferta()->getDisciplina();
                $cursoNoi = $val->getOferta()->getCurso()->getSigla();
                $cNoi++;
            }
            //SABADO MANHA
            if ($dia == 18 && $turno == 9) {
                $cursoSab = $val->getOferta()->getCurso()->getSigla();
                $disciSab = $val->getOferta()->getDisciplina();
                $pSab = $val->getOferta()->getPeriodo();

            }
            //SABADO TARDE
            if ($dia == 18 && $turno == 10) {
                $cursoSabtarde = $val->getOferta()->getCurso()->getSigla();
                $disciSabtarde = $val->getOferta()->getDisciplina();
                $pSabtar = $val->getOferta()->getPeriodo();

            }
        }

        if($cMan > 0){
            $manha = filtrarPeriodosala($pMan, $cursoMan, $disciMan, $dMan);
            if($cMan < 5)
                $manha .= filtrarDiaslivres($dMan,$eM);

        }
        if($cTar > 0){
            $tarde = filtrarPeriodosala($pTar, $cursoTar, $disciTar, $dTar);
            if($cTar < 5)
                $tarde .= filtrarDiaslivres($dTar,$eM);
        }
        if($cNoi > 0){
            $noite = filtrarPeriodosala($pNoi, $cursoNoi, $disciNoi, $dNoi);
            if($cNoi < 5)
                $noite .= filtrarDiaslivres($dNoi,$eM);
        }
        if(!empty($pSab)){
            //preenche o sabado se estiver acom algum registro
            $sabado = filtrarPeriodosalasab($pSab, $cursoSab, $disciSab);
        }
        if(!empty($pSabtar)){
            //preenche o sabado se estiver acom algum registro
            $sabtar = filtrarPeriodosalasab($pSabtar, $cursoSab, $disciSabtarde);
        }
    }

    $html = '
            <style type="text/css">
                @page {	margin: 40px; }                
                p.cabecalho { font-size: 11px; font-family: Calibri; }		
                p.texto1 { font-size: 40px; font-weight: bold; font-family: Calibri; }		
                p.texto2 { font-size: 25px;	font-weight: bold; font-family: Calibri; }
                p.texto3 { font-size: 25px;	font-weight: bold; font-family: Calibri; }
                p.texto3a { font-size: 18px; font-family: Calibri; }
                p.texto3b { font-size: 14px; font-family: Calibri; }
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
                                    '.$campus.' - '.$bloco.'
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
                                <p class="texto1"><center>' . $model->getNome() . '</center></p>
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
                                <td width="300px" height="90px" bgcolor="#c0c0c0"><p class="texto2"><center>TURNO</center></p></td>
                                <td width="380px" height="90px" bgcolor="#c0c0c0"><p class="texto2"><center>CURSO/PERÍODO</center></p></td>
                            </tr>';
    if ($model->getAtivo()) {
        $html .= '
                    <tr>
                        <td width="300px" height="90px"><p class="texto2"><center>Manhã (2ª à 6ª)</center></p></td>
                        <td width="380px" height="90px"><center>' . $manha . ' </center></td>
                    </tr>
                    
                    <tr>
                        <td width="300px" height="90px"><p class="texto2"><center>Tarde (2ª à 6ª)</center></p></td>
                        <td width="380px" height="90px"><center> ' . $tarde . ' </center></td>
                    </tr>
                    <tr>
                        <td width="300px" height="90px"><p class="texto2"><center>Noite (2ª à 6ª)</center></p></td>
                        <td width="380px" height="90px"><center>' . $noite . '</center></td>
                    </tr>
                    <tr>
                        <td width="300px" height="90px"><p class="texto2"><center>Manhã (Sáb)</center></p></td>
                        <td width="380px" height="90px"><center> ' . $sabado . ' </center></td>
                    </tr>';
        if(!empty($pSabtar)){
            $html .= '
                    <tr>
                        <td width="300px" height="90px"><p class="texto2"><center>Tarde (Sáb)</center></p></td>
                        <td width="380px" height="90px"><center> ' . $sabtar . ' </center></td>
                    </tr>';
        }
    } else {
        $html .= '
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Manhã (2ª à 6ª)</center></p></td>
                        <td rowspan="4" width="340px" height="90px"><p class="texto2"><center>DESATIVADA</center></p></td>
                    </tr>                    
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Tarde (2ª à 6ª)</center></p></td>
                    </tr>
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Noite (2ª à 6ª)</center></p></td>
                    </tr>
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Manhã (Sáb)</center></p></td>
                    </tr>';
    }
    $html .= '
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

    return $html;
}

function preencherLabim($model, $salasalocadas, $usolabs)
{
    $nomecampus = strtoupper($model->getPredio()->getUnidade()->getNome());
    $nomebloco = strtoupper($model->getPredio()->getNome());
    //varivaies de controle
    $vazio = '<p class="textoI1">---</p>';
    $agendamento = '<p class="textoI1">AGENDAMENTO</p>';
    $semestre = null;
    $exclusividade = null;
    $exclusivos = null;
    $cexclusiv = 0;
    $tipo = null;
    $manha = [];
    $tarde = [];
    $noite = [];
    $cMan = 0;
    $cTar = 0;
    $cNoi = 0;
    //para fazer a verificação mais adiante
    $day[0] = "SEGUNDA";$day[1] = "TERÇA";$day[2] = "QUARTA";$day[3] = "QUINTA";$day[4] = "SEXTA";$day[5] = "SÁBADO";

    //preenche o vetor da manhã, que sempre virá com salas
    for($i = 0; $i < 6; $i++){
        $manha[$i] = "";
        $tarde[$i] = "";
        $noite[$i] = "";
    }

    for($i = 0; $i < 3; $i++){
        $exclusivos["tarde"][$i] = 0;
        $exclusivos["noite"][$i] = 0;
    }

    if (count($salasalocadas) > 0 || count($usolabs) > 0) {
        //se o contador nao estiver vazio então ele precisa verificar as salas
        //verifica os horarios
        if(count($salasalocadas) > 0){
            foreach ($salasalocadas as $idx => $val) {
                $turno = $val->getOferta()->getTurno()->getId();
                $dia = $val->getOferta()->getDiasemana()->getId();
                //para verificar se tem exclusividade
                $semestre = $val->getSemestre()->getSemestre()->getId();

                //MANHA
                if ($turno == 9) {
                    if ($val->getOferta()->getPeriodo() == 0)
                        $manha[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - REGUL - '.$val->getOferta()->getCurso()->getSigla().' </p><br>';
                    else
                        $manha[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP - '.$val->getOferta()->getCurso()->getSigla().'</p><br>';

                    $cMan++;
                }
                //TARDE
                if ($turno == 10) {
                    if ($val->getOferta()->getPeriodo() == 0)
                        $tarde[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - REGUL - '.$val->getOferta()->getCurso()->getSigla().' </p><br>';
                    else
                        $tarde[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP - '.$val->getOferta()->getCurso()->getSigla().'</p><br>';

                    $cTar++;
                }
                //NOITE
                if ($turno == 11) {
                    if ($val->getOferta()->getPeriodo() == 0)
                        $noite[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - REGUL - '.$val->getOferta()->getCurso()->getSigla().' </p><br>';
                    else
                        $noite[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP - '.$val->getOferta()->getCurso()->getSigla().'</p><br>';

                    $cNoi++;
                }
            }
        }

        //verifica,caso venha vazio, o result set
        if(count($usolabs) > 0){
            foreach ($usolabs as $idx => $d){
                $tipo = $d->getTipouso()->getId();
                //adiciona em um vetor o tipo, virá apenas um tipo exclusivo
                if($tipo == 19){
                    $exclusividade[$cexclusiv]["tipo"] = $tipo;
                    $exclusividade[$cexclusiv]["obs"] = $d->getObservacao();
                    $exclusividade[$cexclusiv]["turno"] = $d->getTurno()->getId();
                    $exclusividade[$cexclusiv]["dia"] = $d->getDia()->getId();
                    $exclusividade[$cexclusiv]["diadesc"] = $d->getDia()->getDescricao();
                    if($d->getTurno()->getId() == 10)
                        $exclusivos["tarde"][0] = 1;
                    if($d->getTurno()->getId() == 11)
                        $exclusivos["noite"][0] = 1;
                }
                if($tipo == 20){
                    $exclusividade[$cexclusiv]["tipo"] = $tipo;
                    $exclusividade[$cexclusiv]["obs"] = $d->getObservacao();
                    $exclusividade[$cexclusiv]["turno"] = $d->getTurno()->getId();
                    if($d->getTurno()->getId() == 10)
                        $exclusivos["tarde"][1] = 1;
                    if($d->getTurno()->getId() == 11)
                        $exclusivos["noite"][1] = 1;

                }
                if($tipo == 21){
                    $exclusividade[$cexclusiv]["tipo"] = $tipo;
                    $exclusividade[$cexclusiv]["obs"] = $d->getObservacao();
                    $exclusivos["tarde"][2] = 1;
                    $exclusivos["noite"][2] = 1;

                }
                $cexclusiv++;
            }
        }

    }

    //joga os dados p/impressao
    $html = '
        <style type="text/css">
                @page {	margin: 40px; }                
                p.cabecalho { font-size: 11px; font-family: Calibri; }		
                p.texto1 { font-size: 40px; font-weight: bold; font-family: Calibri; }		
                p.textoI1 { font-size: 18px; font-weight: bold; font-family: Calibri; }
                p.textoI2 { font-size: 18px; font-weight: bold; font-family: Calibri; }
                p.textoI3 { font-size: 18px; font-weight: bold|italic; font-family: Calibri; }
                p.rodape { font-size: 20px;	font-weight: bold; font-family: Calibri; text-align: center;}        
            </style>
        <div style="border: 5; border-style: double;height: 1122px; width: 793px;">
            <div id="is_cab">
                <table id="cabecalho" style="margin-left: auto; margin-right: auto;">
                    <tbody>
                    <tr><br></tr>
                    <tr>
                        <td>
                            <center><img src="' . BASE_DIR . 'img/unitins_logo_imp.png"></center>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="cabecalho">
                            <center>
                                FUNDAÇÃO UNIVERSIDADE DO TOCANTINS – UNITINS<br>
                                PRÓ-REITORIA DE GRADUAÇÃO<br>
                                '.$nomecampus.' - '.$nomebloco.'
                            </center>
                            </p>
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
                            <p class="texto1"><center>' . $model->getNome() .'</center></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div id="is_content">
                <table id="content" border="1" style="margin-left: auto; margin-right: auto; border-style: double;">
                    <tbody>
                        <tr>
                            <td colspan="2" width="650px" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO MATUTINO</center></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>DIA</center></p>
                            </td>
                            <td width="450px" height="50px"  bgcolor="#c0c0c0">
                                <p class="textoI1"><center> DISCIPLINA/CURSO/PERÍODO </center></p>
                            </td>
                        </tr>';
    //verifica se tem registros pela manha, para economizar espaço na impressao
    if($cMan > 0){
        foreach ($manha as $idx => $val ) {
            if (!empty($val)) {
                $html .= '
                        <tr>
                            <td width="200px" height="50px">
                                <p class="textoI1"><center>' . $day[$idx] . '</center></p>
                            </td>
                            <td width="450px" height="50px">
                                <center>' . $val . '</center>
                            </td>
                        </tr>
            ';
            }
        }
    }
    else{
        $html .= '
                        <tr>
                            <td colspan="2" height="70px">
                                <p class="texto2"><center> '.$agendamento.'</center></p>
                            </td>
                        </tr>
            ';
    }
    if($cTar==0 && $cNoi==0 && $cexclusiv==0){
        $html .= '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO/NORTURNO</center></p>
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="2" height="70px">
                                <p class="texto2"><center> '.$agendamento.'</center></p>
                            </td>
                        </tr>  
        ';
    }
    else{
        //verificar quais os turnos que estão
        if($cTar > 0){
            //aqui vai verificar as alocações vespertinas
            $html .= '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
            ';
            foreach ($tarde as $idx => $val) {
                if(!empty($val)){
                    $html .= '
                        <tr>
                            <td width="200px" height="50px">
                                <p class="textoI1"><center>'.$day[$idx].'</center></p>
                            </td>
                            <td width="450px" height="50px">
                                <center>'.$val.'</center>
                            </td>
                        </tr>
                    ';
                }
            }
            //vai verificar se alguma monitoria especial esta no turno vespertino
        }
        if($exclusivos["tarde"][0] == 1)
            $html .= monitoria($exclusividade, 10,$day, $cTar);
        if ($exclusivos["tarde"][1] == 1)
            $html .= usoComum($exclusividade, 10, $cTar);

        //
        if($cNoi > 0){
            //aqui vai verificar as alocações noturnas
            $html .= '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr> 
            ';
            foreach ($noite as $idx => $val) {
                if(!empty($val)){
                    $html .= '
                        <tr>
                            <td width="200px" height="50px">
                                <p class="textoI1"><center>'.$day[$idx].'</center></p>
                            </td>
                            <td width="450px" height="50px">
                                <center>'.$val.'</center>
                            </td>
                        </tr>
                    ';
                }
            }
        }
        if($exclusivos["noite"][0] == 1)
            $html .= monitoria($exclusividade, 11,$day, $cNoi);
        if ($exclusivos["noite"][1] == 1)
            $html .= usoComum($exclusividade, 11, $cNoi);

        //verifica se tem algum uso EXCLUSIVO
        if($exclusivos["noite"][2] == 1)
            $html .= exclusivo($exclusividade);

    }
    $html.= '
                        </tbody>
                    </table>
                </div>
                <p class="rodape" style="position: bottom;">CURSOS PRESENCIAIS – UNITINS</p>
            </div>';

    return $html;
}

function prencherOrientacao($model,$salasalocadas){
    $nomecampus = strtoupper($model->getPredio()->getUnidade()->getNome());
    $nomebloco = strtoupper($model->getPredio()->getNome());
    //varivaies de controle
    $manha = [];
    $tarde = [];
    $noite = [];
    $cMan = 0;
    $cTar = 0;
    $cNoi = 0;
    //para fazer a verificação mais adiante
    $day[0] = "SEGUNDA";$day[1] = "TERÇA";$day[2] = "QUARTA";$day[3] = "QUINTA";$day[4] = "SEXTA";$day[5] = "SÁBADO";

    if (count($salasalocadas) > 0) {
        //se o contador nao estiver vazio então ele precisa verificar as salas
        //verifica os horarios
        if(count($salasalocadas) > 0){
            foreach ($salasalocadas as $idx => $val) {
                $turno = $val->getOferta()->getTurno()->getId();
                $dia = $val->getOferta()->getDiasemana()->getId();
                //para verificar se tem exclusividade
                $semestre = $val->getSemestre()->getSemestre()->getId();

                //MANHA
                if ($turno == 9) {
                    if ($val->getOferta()->getPeriodo() == 0)
                        $manha[$cMan]['disciplina'] = $val->getOferta()->getDisciplina().' - REGUL';
                    else
                        $manha[$cMan]['disciplina']  = $val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP';

                    $manha[$cMan]['iddia'] = $dia;
                    $manha[$cMan]['dia'] = $day[$dia - 13];
                    $manha[$cMan]['curso'] = $val->getOferta()->getCurso()->getSigla();
                    $cMan++;
                }
                //TARDE
                if ($turno == 10) {
                    if ($val->getOferta()->getPeriodo() == 0)
                        $tarde[$cTar]['disciplina'] = $val->getOferta()->getDisciplina().' - REGUL';
                    else
                        $tarde[$cTar]['disciplina']  = $val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP';

                    $tarde[$cTar]['iddia'] = $dia;
                    $tarde[$cTar]['dia'] = $day[$dia - 13];
                    $tarde[$cTar]['curso'] = $val->getOferta()->getCurso()->getSigla();
                    $cTar++;

                }
                //NOITE
                if ($turno == 11) {
                    if ($val->getOferta()->getPeriodo() == 0)
                        $noite[$cNoi]['disciplina'] = $val->getOferta()->getDisciplina().' - REGUL';
                    else
                        $noite[$cNoi]['disciplina']  = $val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP';

                    $noite[$cNoi]['iddia'] = $dia;
                    $noite[$cNoi]['dia'] = $day[$dia - 13];
                    $noite[$cNoi]['curso'] = $val->getOferta()->getCurso()->getSigla();
                    $cNoi++;
                }
            }
        }
    }

    if($cMan > 0)
        $manha = organizarOrientacao($manha, 'iddia');

    if($cTar > 0)
        $tarde = organizarOrientacao($tarde, 'iddia');

    if($cNoi > 0)
        $noite = organizarOrientacao($noite, 'iddia');

    //joga os dados p/impressao
    $html = '
        <style type="text/css">
            @page {	margin: 40px; }
            p.cabecalho { font-size: 11px; font-family: Calibri; }
            p.texto1 { font-size: 40px; font-weight: bold; font-family: Calibri; }
            p.textoI1 { font-size: 18px; font-weight: bold; font-family: Calibri; }
            p.textoI2 { font-size: 18px; font-weight: bold; font-family: Calibri; }
            p.textoI3 { font-size: 18px; font-weight: bold|italic; font-family: Calibri; }
            p.rodape { font-size: 20px;	font-weight: bold; font-family: Calibri; text-align: center;}
        </style>
        <div style="border: 5; border-style: double;height: 1122px; width: 793px;">
            <div id="is_cab">
                <table id="cabecalho" style="margin-left: auto; margin-right: auto;">
                    <tbody>
                    <tr><br></tr>
                    <tr>
                        <td>
                            <center><img src="' . BASE_DIR . 'img/unitins_logo_imp.png"></center>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="cabecalho">
                            <center>
                                FUNDAÇÃO UNIVERSIDADE DO TOCANTINS – UNITINS<br>
                                PRÓ-REITORIA DE GRADUAÇÃO<br>
                                '.$nomecampus.' - '.$nomebloco.'
                            </center>
                            </p>
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
                            <p class="texto1"><center>' . $model->getNome() . '</center></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div id="is_content">
                <table id="content" border="1" style="margin-left: auto; margin-right: auto; border-style: double;">
                    <tbody>
                    <tr>
                        <td colspan="3" width="650px" height="50px" bgcolor="#c0c0c0">
                            <p class="textoI1"><center>PERÍODO MATUTINO</center></p>
                        </td>
                    </tr>
                    <tr>
                        <td width="130px" height="50px" bgcolor="#c0c0c0">
                            <p class="textoI1"><center>DIA</center></p>
                        </td>
                        <td width="400px" height="50px"  bgcolor="#c0c0c0">
                            <p class="textoI1"><center> DISCIPLINA/PERÍODO </center></p>
                        </td>
                        <td width="120px" height="50px"  bgcolor="#c0c0c0">
                            <p class="textoI1"><center> CURSO </center></p>
                        </td>
                    </tr>';

    foreach ($manha as $val => $key){
        $html .= '
                    <tr>
                        <td width="130px" height="50px">
                            <p class="textoI1"><center>'.$key["dia"].'</center></p>
                        </td>
                        <td width="400px" height="50px">
                            <p class="textoI1"><center>'.$key["disciplina"].'</center></p>
                        </td>
                        <td width="120px" height="50px" >
                            <p class="textoI1"><center>'.$key["curso"].' </center></p>
                        </td>
                    </tr>
        
        ';
    }
    if($cTar == 0 && $cNoi == 0){
        $html .= '
                    <tr>
                        <td colspan="3" height="50px" bgcolor="#c0c0c0">
                            <p class="textoI1"><center>PERÍODO VESPERTINO/NORTURNO</center></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="50px">
                            <p class="texto2"><center>---</center></p>
                        </td>
                    </tr>
        ';
    }
    else{
        //verifica a tarde
        $html .= '
                    <tr>
                        <td colspan="3" height="50px" bgcolor="#c0c0c0">
                            <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                        </td>
                    </tr>';

        if($cTar > 0){
            foreach ($tarde as $val => $key){
                $html .= '
                    <tr>
                        <td width="130px" height="50px">
                            <p class="textoI1"><center>'.$key["dia"].'</center></p>
                        </td>
                        <td width="400px" height="50px">
                            <p class="textoI1"><center>'.$key["disciplina"].'</center></p>
                        </td>
                        <td width="120px" height="50px" >
                            <p class="textoI1"><center>'.$key["curso"].' </center></p>
                        </td>
                    </tr>';
            }
        }
        else{
            $html .= '
                    <tr>
                        <td colspan="3" height="50px">
                            <p class="texto2"><center>---</center></p>
                        </td>
                    </tr>';
        }
        //verifica a noite
        $html .= '
                    <tr>
                        <td colspan="3" height="50px" bgcolor="#c0c0c0">
                            <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                        </td>
                    </tr>';
        if($cNoi > 0){
            foreach ($noite as $val => $key){
                $html .= '
                    <tr>
                        <td width="130px" height="50px">
                            <p class="textoI1"><center>'.$key["dia"].'</center></p>
                        </td>
                        <td width="400px" height="50px">
                            <p class="textoI1"><center>'.$key["disciplina"].'</center></p>
                        </td>
                        <td width="120px" height="50px" >
                            <p class="textoI1"><center>'.$key["curso"].' </center></p>
                        </td>
                    </tr>';
            }

        }
        else{
            $html .= '
                    <tr>
                        <td colspan="3" height="50px">
                            <p class="texto2"><center>---</center></p>
                        </td>
                    </tr>';
        }



    }



    $html .= '
                </table>
            </div>
            <p class="rodape" style="position: bottom;">CURSOS PRESENCIAIS – UNITINS</p>
        </div>
        ';

    return $html;
}

//metodo que pegará as monitorias
function monitoria($exclusividade,$turno,$day,$cont){
    $html = "";
    $reg = 0;
    //tarde
    if($turno == 10 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
        ';
    }
    //noite
    if($turno == 11 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr> 
        ';
    }
    foreach ($exclusividade as $idx => $val){
        if($val["tipo"] == 19 && $val["turno"] == $turno ){
            $html .= '
                       <tr>
                            <td width="200px" height="50px">
                                <p class="textoI1"><center>'.$day[$val["dia"]-13].'</center></p>
                            </td>
                            <td width="450px" height="50px">
                                <center><p class="textoI2">'.$val["obs"].'</p></center>
                            </td>
                        </tr>
            ';
            $reg++;
        }
    }
    //se o contador vier ZERADO é pq nao tem registros
    if($reg == 0) $html = "";
    return $html;
}

//metodo que filtrara alocações de sala
function filtrarPeriodosala($paramPeriodos, $cursoTurno, $disciplinas, $diaturno){
    $periodos = array_unique($paramPeriodos);
    $retorno = "";
    if(count($periodos) > 1){
        $texto = $cursoTurno." ";
        foreach ( $periodos as $val => $key){
            if($val == 0)
                $texto .= "REGUL";
            else
                $texto .= $val;
            //coloca a barra
            if($key < count($periodos)-1)
                $texto .= "/";
        }
        $retorno = '<p class="texto3">' . $texto . ' ºP </p>';
    }
    else{
        if ($periodos[0] == 0)
            $retorno = '<p class="texto3">' . $cursoTurno . ' REGUL. </p>';
        else
            $retorno = '<p class="texto3">' . $cursoTurno. " " . $periodos[0] . 'ºP</p>';
    }
    //constroi as regularizações que aparecerão
    $auxvetor = [];
    $pos = 0;
    for($i=0; $i < count($paramPeriodos); $i++){
        //se o periodo for regularização precisa adicionar as inormações
        if($paramPeriodos[$i] == 0){
            $auxvetor[$pos]["diaid"] = $diaturno[$i]["id"];
            $auxvetor[$pos]["dia"] = $diaturno[$i]["desc"];
            $auxvetor[$pos]["disciplina"] = $disciplinas[$i];
            $pos++;
        }
    }
    if(!empty($auxvetor)){
        $auxvetor = organizarOrientacao($auxvetor, "diaid");
        foreach ($auxvetor as $key => $val){
            //se não estiver vazio ele ira fazer a filtragem;
            $texto = '<br><p class="texto3b">' . $val["dia"]. ': ' . $val["disciplina"]. '</p>';
            $retorno .= $texto;
        }
    }

    return $retorno;
}

//metodo que filtrara alocações de sala no sabado
function filtrarPeriodosalasab($periodo, $curso, $disciplina){
    //preenche o sabado se estiver acom algum registro
    $texto = $curso." ";
    if($periodo == 0)
        $texto .= " REGUL";
    else
        $texto .= " ".$periodo."ºP";

    $retorno = '<p class="texto3">' .$texto. '</p>';

    if($periodo == 0){
        $texto = '<br><p class="texto3b">' .$disciplina. '</p>';
        $retorno .= $texto;
    }
    return $retorno;
}

//metodo que filtra os dias livres
function filtrarDiaslivres($diaturno, $eM){
    //se for menor que 5 é porque tem dia livre
    $texto = '<br><p class="texto3a"><i>LIVRE: ';
    for ($i = 13; $i <= 17; $i++) {
        $tem = false;
        foreach ($diaturno as $k => $v) {
            if ($v["id"] == $i) {
                $tem = true;
            }
        }
        if (!$tem) {
            $dia = $eM->find('Registro', $i);
            $pieces = explode("-", $dia->getDescricao());
            $texto .= '' . $pieces[0] . '';
            if ($i < 17)
                $texto .= '/';
        }
    }
    $texto .= '</i></p>';
    return $texto;

}

//metodo que pegará os usos em comum
function usoComum($exclusividade,$turno,$cont){
    $html = "";
    $reg = 0;
    //tarde
    if($turno == 10 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
        ';
    }
    //noite
    if($turno == 11 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="50px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr> 
        ';
    }
    foreach ($exclusividade as $idx => $val){
        if($val["tipo"] == 20 && $val["turno"] == $turno ){
            $html .= '
                       <tr>
                            <td colspan="2" height="70px">
                                <center><p class="textoI2">'.$val["obs"].'</p></center>
                            </td>
                        </tr>
            ';
            $reg++;
        }
    }
    //se o contador vier ZERADO é pq nao tem registros
    if($reg == 0) $html = "";
    return $html;

}

//metodo que pegará os usos exclusivos
function exclusivo($exclusividade){
    foreach ($exclusividade as $idx => $val){
        if($val["tipo"] == 21){ // quando é exclusividade
            $html = ' 
                        <tr>
                            <td colspan="2" height="70px" bgcolor="#c0c0c0">
                                <p class="textoI3"><center><b>'.$val["obs"].'</b></center></p>
                            </td>
                        </tr> 
            ';
        }
    }
    return $html;

}

//metodo que organiza um array pela chave selecionada
function organizarOrientacao($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }
    return $new_array;
}

