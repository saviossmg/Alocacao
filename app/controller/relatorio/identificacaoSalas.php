<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 04/11/2017
 * Arquivo responsavel por imprimir o relatorio de...
 */
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';
require_once BASE_DIR . 'mpdf/mpdf.php';

$arquivo = "impressao/IDENTIFICACAO_SALAS.pdf";

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

    $data = [];

    $mpdf = new mPDF();
    $htmlx = "";

    //contadores
    $total = count($salas);
    $i = 1;

    $semestre = $entityManager->find('Vwsemestre', $_POST['semestre']);

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

/*        if ($tipoSala == 1) {
            $ret = preencherSala($model,$rs,$entityManager);
        }*/
        if ($tipoSala == 2){
            $ret = preencherLabim($model,$rs,$entityManager);
        }

        if ($i <= $total) {
            $i++;
            $htmlx .= $ret;
        }
    }

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
            if ($turno == 9) {
                $dMan[$cMan] = $val->getOferta()->getDiasemana()->getId();
                if ($val->getOferta()->getPeriodo() == 0)
                    $manha = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . ' REGUL. </p><br>';
                else
                    $manha = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . " " . $val->getOferta()->getPeriodo() . 'ºP</p><br>';

                $cMan++;
            }
            //TARDE
            if ($turno == 10) {
                $dTar[$cTar] = $val->getOferta()->getDiasemana()->getId();
                if ($val->getOferta()->getPeriodo() == 0)
                    $tarde = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . ' REGUL. </p><br>';
                else
                    $tarde = '<p class="texto3">' . $val->getOferta()->getCurso()->getSigla() . " " . $val->getOferta()->getPeriodo() . 'ºP</p><br>';

                $cTar++;
            }
            //NOITE
            if ($turno == 11) {
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
    //varivaies de controle
    $vazio = '<p class="textoI1">---</p>';
    $agendamento = '<p class="textoI1">AGENDAMENTO</p>';
    $semestre = null;
    $exclusividade = null;
    $tipo = null;
    $manha = [];
    $tarde = [];
    $noite = [];
    $cTar = 0;
    $cNoi = 0;
    //para fazer a verificação mais adiante
    $day[0] = "SEGUNDA";$day[1] = "TERÇA";$day[2] = "QUARTA";$day[3] = "QUINTA";$day[4] = "SEXTA";$day[5] = "SÁBADO";

    //preenche o vetor da manhã, que sempre virá com salas
    for($i = 0; $i < 6; $i++){
        $manha[$i] = $vazio;
        $tarde[$i] = "";
        $noite[$i] = "";
    }

    if (count($salasalocadas) > 0) {
        //se o contador nao estiver vazio então ele precisa verificar as salas
        //verifica os horarios
        foreach ($salasalocadas as $idx => $val) {
            $turno = $val->getOferta()->getTurno()->getId();
            $dia = $val->getOferta()->getDiasemana()->getId();
            //MANHA
            if ($turno == 9) {
                if ($val->getOferta()->getPeriodo() == 0)
                    $manha[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - REGUL - '.$val->getOferta()->getCurso()->getSigla().' </p><br>';
                else
                    $manha[$dia-13] = '<p class="textoI2">'.$val->getOferta()->getDisciplina().' - '.$val->getOferta()->getPeriodo().'ºP - '.$val->getOferta()->getCurso()->getSigla().'</p><br>';

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
    //joga os dados p/impressao
    $html = '
        <style type="text/css">
                @page {	margin: 40px; }                
                p.cabecalho { font-size: 11px; font-family: Calibri; }		
                p.texto1 { font-size: 44px; font-weight: bold; font-family: Calibri; }		
                p.textoI1 { font-size: 24px; font-weight: bold; font-family: Calibri; }
                p.textoI2 { font-size: 20px; font-weight: bold; font-family: Calibri; }
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
                                CÂMPUS GRACIOSA
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
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>SEGUNDA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[0].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>TERÇA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[1].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>QUARTA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[2].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>QUINTA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[3].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>SEXTA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[4].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>SÁBADO</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[5].'</center>
                            </td>
                        </tr>';
    if($cTar==0 && $cNoi==0){
        $html .= '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO/NORTURNO</center></p>
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="2" height="60px">
                                <p class="texto2"><center> '.$agendamento.'</center></p>
                            </td>
                        </tr>  
        ';
    }
    else{
        if($cTar > 0){
            //
        }
        else{
            $html .= '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="2" height="60px">
                                <p class="texto2"><center>'.$agendamento.'</center></p>
                            </td>
                        </tr>
            ';
        }
        //
        if($cNoi > 0){
            //
        }
        else{
            $html .= '
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="textoI1"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="2" height="60px">
                                <p class="texto2"><center>'.$agendamento.'</center></p>
                            </td>
                        </tr> 
            ';
        }
    }
    $html.= '
                        </tbody>
                    </table>
                </div>
                <p class="rodape" style="position: bottom;">CURSOS PRESENCIAIS – UNITINS</p>
            </div>';

    return $html;
}

//VERIFICA SE O LABORATORIO TEM ALGUM USO EXCLUSIVO
/*
foreach ($salasalocadas as $idx => $val){
    $semestre = $val->getSemestre()->getSemestre()->getId();
    break;
}

//procura se tem registros
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
if(!empty($rs)){
    foreach ($rs as $idx => $val){
        $tipo = $val->getTipouso()->getId();
        break;
    }
    if($tipo == 19){
    }
    if($tipo == 20){
    }
    if($tipo == 21){
    }
}
*/

/*
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
       */

/*
$html = '
        <style type="text/css">
                @page {	margin: 40px; }                
                p.cabecalho { font-size: 11px; font-family: Calibri; }		
                p.texto1 { font-size: 44px; font-weight: bold; font-family: Calibri; }		
                p.textoI1 { font-size: 24px; font-weight: bold; font-family: Calibri; }
                p.textoI2 { font-size: 18px; font-weight: bold; font-family: Calibri; }
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
                                CÂMPUS GRACIOSA
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
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>SEGUNDA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[0].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>TERÇA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[1].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>QUARTA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[2].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>QUINTA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[3].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>SEXTA</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[4].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px" height="60px">
                                <p class="textoI1"><center>SÁBADO</center></p>
                            </td>
                            <td width="450px" height="60px">
                                <center>'.$manha[5].'</center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="texto2"><center>PERÍODO VESPERTINO</center></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="60px">
                                <p class="texto2"><center>'.$agendamento.'</center></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="60px" bgcolor="#c0c0c0">
                                <p class="texto2"><center>PERÍODO NOTURNO</center></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="60px">
                                <p class="texto2"><center>'.$agendamento.'</center></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="rodape" style="position: bottom;">CURSOS PRESENCIAIS – UNITINS</p>
        </div>';

*/