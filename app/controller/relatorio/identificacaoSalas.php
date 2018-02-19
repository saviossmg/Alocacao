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
            $ret = preencherLabim($model,$rs,$entityManager);
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
    $cMan = 0;
    $cTar = 0;
    $cNoi = 0;
    $dMan = [];
    $dTar = [];
    $dNoi = [];

    if (count($salasalocadas) > 0) {
        //se o contador nao estiver vazio então ele precisa verificar as salas
        //verifica os horarios
        foreach ($salasalocadas as $idx => $val) {
            $turno = $val->getOferta()->getTurno()->getId();
            $dia = $val->getOferta()->getDiasemana()->getId();
            //MANHA
            if ($turno == 9 && $dia != 18) {
                $dMan[$cMan] = $val->getOferta()->getDiasemana()->getId();
                if ($val->getOferta()->getPeriodo() == 0)
                    $manha = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . ' REGUL. </p><br>';
                else
                    $manha = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . " " . $val->getOferta()->getPeriodo() . 'ºP</p><br>';

                $cMan++;
            }
            //TARDE
            if ($turno == 10 && $dia != 18) {
                $dTar[$cTar] = $val->getOferta()->getDiasemana()->getId();
                if ($val->getOferta()->getPeriodo() == 0)
                    $tarde = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . ' REGUL. </p><br>';
                else
                    $tarde = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . " " . $val->getOferta()->getPeriodo() . 'ºP</p><br>';

                $cTar++;
            }
            //NOITE
            if ($turno == 11 && $dia != 18) {
                $dNoi[$cNoi] = $val->getOferta()->getDiasemana()->getId();
                if ($val->getOferta()->getPeriodo() == 0)
                    $noite = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . ' REGUL. </p><br>';
                else
                    $noite = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . " " . $val->getOferta()->getPeriodo() . 'ºP</p><br>';

                $cNoi++;
            }
            //SABADO
            if ($dia == 18) {
                if ($val->getOferta()->getPeriodo() == 0)
                    $sabado = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . ' REGUL. </p><br>';
                else
                    $sabado = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . " " . $val->getOferta()->getPeriodo() . 'ºP</p><br>';
            }
        }
        //depois verifica por filtro para saber se tem dias livres
        if ($cMan > 0) {
            //se for menor que 5 é porque tem dia livre
            if ($cMan < 5)
                $manha .= '<p class="texto3a"><i>LIVRE: ';
            for ($i = 13; $i <= 17; $i++) {
                $tem = false;
                foreach ($dMan as $v) {
                    if ($v == $i) {
                        $tem = true;
                    }
                }
                if (!$tem) {
                    $dia = $eM->find('Registro', $i);
                    $pieces = explode("-", $dia->getDescricao());
                    $manha .= '' . $pieces[0] . '';
                    if ($i < 17)
                        $manha .= '/';
                }
            }
            $manha .= '</i></p>';
        }
        if ($cTar > 0) {
            //se for menor que 5 é porque tem dia livre
            if ($cTar < 5)
                $tarde .= '<p class="texto3a"><i>LIVRE: ';
            for ($i = 13; $i <= 17; $i++) {
                $tem = false;
                foreach ($dTar as $v) {
                    if ($v == $i) {
                        $tem = true;
                    }
                }
                if (!$tem) {
                    $dia = $eM->find('Registro', $i);
                    $pieces = explode("-", $dia->getDescricao());
                    $tarde .= '' . $pieces[0] . '';
                    if ($i < 17)
                        $tarde .= '/';
                }
            }
            $tarde .= '</i></p>';
        }
        if ($cNoi > 0) {
            //se for menor que 5 é porque tem dia livre
            if ($cNoi < 5)
                $noite .= '<p class="texto3a"><i>LIVRE: ';
            for ($i = 13; $i <= 17; $i++) {
                $tem = false;
                foreach ($dNoi as $v) {
                    if ($v == $i) {
                        $tem = true;
                    }
                }
                if (!$tem) {
                    $dia = $eM->find('Registro', $i);
                    $pieces = explode("-", $dia->getDescricao());
                    $noite .= '' . $pieces[0] . '';
                    if ($i < 17)
                        $noite .= '/';
                }
            }
            $noite .= '</i></p>';
        }

    }

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
                                <td width="340px" height="90px" bgcolor="#c0c0c0"><p class="texto2"><center>TURNO</center></p></td>
                                <td width="340px" height="90px" bgcolor="#c0c0c0"><p class="texto2"><center>CURSO/PERÍODO</center></p></td>
                            </tr>';
    if ($model->getAtivo()) {
        $html .= '
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Manhã (2ª à 6ª)</center></p></td>
                        <td width="340px" height="90px"><center>' . $manha . ' </center></td>
                    </tr>
                    
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Tarde (2ª à 6ª)</center></p></td>
                        <td width="340px" height="90px"><center> ' . $tarde . ' </center></td>
                    </tr>
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Noite (2ª à 6ª)</center></p></td>
                        <td width="340px" height="90px"><center>' . $noite . '</center></td>
                    </tr>
                    <tr>
                        <td width="340px" height="90px"><p class="texto2"><center>Manhã (Sáb)</center></p></td>
                        <td width="340px" height="90px"><center> ' . $sabado . ' </center></td>
                    </tr>';
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

function preencherLabim($model, $salasalocadas, $eM)
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

    if (count($salasalocadas) > 0) {
        //se o contador nao estiver vazio então ele precisa verificar as salas
        //verifica os horarios
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

        //procura se tem registros de laboratorios especificos
        $qb = $eM->createQueryBuilder();
        $qb->select("l, S, La, Tu")
            ->from('Laboratorio', "l")
            ->innerJoin("l.semestre", "S")
            ->innerJoin("l.laboratorio", "La")
            ->innerJoin("l.tipouso", "Tu")
            ->where('S.id = :semestre')
            ->andWhere('La.id = :lab')
            ->setParameter('semestre', $semestre)
            ->setParameter('lab', $model->getId());
        $rs = $qb->getQuery()->getResult();


        //verifica,caso venha vazio, o result set
        if(!empty($rs) || count($rs)>0){
            foreach ($rs as $idx => $d){
                $tipo = $d->getTipouso()->getId();
                //adiciona em um vetor o tipo, virá apenas um tipo exclusivo
                if($tipo == 19){
                    $exclusividade[$cexclusiv]["tipo"] = $tipo;
                    $exclusividade[$cexclusiv]["obs"] = $d->getObservacao();
                    $exclusividade[$cexclusiv]["turno"] = $d->getTurno()->getId();
                    $exclusividade[$cexclusiv]["dia"] = $d->getDia()->getId();
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
                p.texto1 { font-size: 44px; font-weight: bold; font-family: Calibri; }		
                p.textoI1 { font-size: 24px; font-weight: bold; font-family: Calibri; }
                p.textoI2 { font-size: 20px; font-weight: bold; font-family: Calibri; }
                p.textoI3 { font-size: 20px; font-weight: bold|italic; font-family: Calibri; }
                p.rodape { font-size: 22px;	font-weight: bold; font-family: Calibri; text-align: center;}        
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
                            <td colspan="2" width="650px" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO MATUTINO</center></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>DIA</center></p>
                            </td>
                            <td width="450px" height="60px"  bgcolor="#c0c0c0">
                                <p class="textoI1"><center> DISCIPLINA/CURSO/PERÍODO </center></p>
                            </td>
                        </tr>';
    //verifica se tem registros pela manha, para economizar espaço na impressao
    if($cMan > 0){
        foreach ($manha as $idx => $val ) {
            if (!empty($val)) {
                $html .= '
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>' . $day[$idx] . '</center></p>
                            </td>
                            <td width="450px" height="60px">
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
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
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
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
            ';
            foreach ($tarde as $idx => $val) {
                if(!empty($val)){
                    $html .= '
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>'.$day[$idx].'</center></p>
                            </td>
                            <td width="450px" height="60px">
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
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr> 
            ';
            foreach ($noite as $idx => $val) {
                if(!empty($val)){
                    $html .= '
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>'.$day[$idx].'</center></p>
                            </td>
                            <td width="450px" height="60px">
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

//metodo que pegará as monitorias
function monitoria($exclusividade,$turno,$day,$cont){
    $html = "";
    $reg = 0;
    //tarde
    if($turno == 10 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
        ';
    }
    //noite
    if($turno == 11 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr> 
        ';
    }
    foreach ($exclusividade as $idx => $val){
        if($val["tipo"] == 19 && $val["turno"] == $turno ){
            $html .= '
                       <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>'.$day[$idx].'</center></p>
                            </td>
                            <td width="450px" height="60px">
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

//metodo que pegará os usos em comum
function usoComum($exclusividade,$turno,$cont){
    $html = "";
    $reg = 0;
    //tarde
    if($turno == 10 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
        ';
    }
    //noite
    if($turno == 11 && $cont == 0){
        $html = '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
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