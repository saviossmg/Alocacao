<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 05/12/2017
 * Arquivo responsavel por imprimir o relatorio de mapa de cursos
 */
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
require_once BASE_DIR . 'mpdf/mpdf.php';

$arquivo = "impressao/";

//variavel para mostrar o resultado final
$resultado = [];
$mensagem = "";
$hmtlx = "";

try {

    //parametros capturados via post
    $cursos = $_POST['cursos'];

    //validação dos campos
    if (empty($cursos) || empty($_POST['semestre']) || empty($_POST['campus'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($cursos)) {
            $mensagem .= "1 - Cursos <br>";
        }
        if (empty($_POST['semestre'])) {
            $mensagem .= "2 - Semestre <br>";
        }
        if (empty($_POST['campus'])) {
            $mensagem .= "3 - Campus <br>";
        }
        throw new Exception($mensagem);
    }

    //deixa em modo paisagem
    $mpdf = new mPDF('c', 'A4-L');

    //Instancia os objetos
    $semestre = $entityManager->find('Vwsemestre', $_POST['semestre']);
    $campus = $entityManager->find('Unidade', $_POST['campus']);

    foreach ($cursos as $idx => $val) {
        $model = $entityManager->find('Vwcurso', $val);
        //seleciona as salas
        $salas = getSalalab($entityManager,$semestre->getId(), $val,1);
        //seleciona os labs
        $labs = getSalalab($entityManager,$semestre->getId(), $val,2);
        //seleciona as obs de lab
        $obsLabs = getObservacao($entityManager,$semestre->getId());
        //se tiver salas, ele imprime o relatorio com as salas
        if(count($salas) > 0 || count($labs) > 0)
            $htmlx .= mapaSala($salas,$labs,$model->getNome(),$semestre->getDescricao());
        //se tiver labs, ele imprime
        if(count($labs) > 0)
            $htmlx .= mapaLabim($labs,$model->getNome(),$semestre->getDescricao(),$obsLabs);
    }
    $mpdf->WriteHTML($htmlx);

    $nomesem = str_replace("/", ".", $semestre->getDescricao());
    $arquivo .= "MAPA_USO_CURSO-".$campus->getNome()."-".$nomesem.".pdf";

    //$arquivo .= "MAPA_USO_CURSO.pdf";
    $filename = BASE_DIR . $arquivo;
    $mpdf->Output($filename, 'F');
    $mensagem = "Arquivos impresso com sucesso!";
    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $arquivo];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

//Impressao de salas
function mapaSala($salas, $labs, $nomeCurso, $nomeSemestre){
    //turnos
    $manha = [];
    $tarde = [];
    $noite = [];
    $sabman = [];
    $sabtar = [];

    //contadores dos turnos
    $cmanha = 0;
    $ctarde = 0;
    $cnoite = 0;
    $csabman = 0;
    $csabtar = 0;

    $salaTitulo = [];
    $contSala = 0;

    //gera a lista das salas eliminando as repetições - essa sempre estará cheia
    if(count($salas) > 0){
        $retorno = getFiltroSalas($salas, $salaTitulo,$contSala );
        $salaTitulo = $retorno["array"];
        $contSala = $retorno["contador"];

    }
    //gera uma lista dos labs
    if(count($labs) > 0){
        $retorno = getFiltroSalas($labs, $salaTitulo,$contSala );
        $salaTitulo = $retorno["array"];
        $contSala = $retorno["contador"];
    }

    //prepara o array dos turnos, alinhando com os das salas
    foreach ($salaTitulo as $idx => $val){
        //manha
        $manha[$idx]["idsala"] = $val["idsala"];
        $manha[$idx]["periodos"] = null;
        //tarde
        $tarde[$idx]["idsala"] = $val["idsala"];
        $tarde[$idx]["periodos"] = null;
        //noite
        $noite[$idx]["idsala"] = $val["idsala"];
        $noite[$idx]["periodos"] = null;
        //sabado manha
        $sabman[$idx]["idsala"] = $val["idsala"];
        $sabman[$idx]["periodos"] = null;
        //sabado tarde
        $sabtar[$idx]["idsala"] = $val["idsala"];
        $sabtar[$idx]["periodos"] = null;
    }

    //preenche o array dos turnos com os periodos, com base na sala
    if(count($salas)>0){
        $retorno = preencherPeriodo($salas,$salaTitulo,$manha,$tarde,$noite,$sabman,$sabtar,$cmanha,$ctarde,$cnoite,$csabman,$csabtar);
        $manha = $retorno["manha"];
        $tarde = $retorno["tarde"];
        $noite = $retorno["noite"];
        $sabman = $retorno["sabman"];
        $sabtar = $retorno["sabtar"];
        $cmanha = $retorno["cmanha"];
        $ctarde = $retorno["ctarde"];
        $cnoite = $retorno["cnoite"];
        $csabman = $retorno["csabman"];
        $csabtar = $retorno["csabtar"];
    }

    if(count($labs)>0){
        $retorno = preencherPeriodo($labs,$salaTitulo,$manha,$tarde,$noite,$sabman,$sabtar,$cmanha,$ctarde,$cnoite,$csabman,$csabtar);
        $manha = $retorno["manha"];
        $tarde = $retorno["tarde"];
        $noite = $retorno["noite"];
        $sabman = $retorno["sabman"];
        $sabtar = $retorno["sabtar"];
        $cmanha = $retorno["cmanha"];
        $ctarde = $retorno["ctarde"];
        $cnoite = $retorno["cnoite"];
        $csabman = $retorno["csabman"];
        $csabtar = $retorno["csabtar"];
    }

    //pega o $salaTitulo e preenche a parte de periodos com base nos periodos
    for($i=0;$i<$contSala;$i++){
        //verifica se não vem vazio para chamar e sobrescrever o que já vem na posicao do turno
        if(!empty($manha[$i]["periodos"]))
            $salaTitulo[$i]["manha"] = preencheColunaTurno($manha[$i]["periodos"]);

        if(!empty($tarde[$i]["periodos"]))
            $salaTitulo[$i]["tarde"] = preencheColunaTurno($tarde[$i]["periodos"]);

        if(!empty($noite[$i]["periodos"]))
            $salaTitulo[$i]["noite"] = preencheColunaTurno($noite[$i]["periodos"]);

        if(!empty($sabman[$i]["periodos"]))
            $salaTitulo[$i]["sabman"] = preencheColunaTurno($sabman[$i]["periodos"]);

        if(!empty($sabtar[$i]["periodos"]))
            $salaTitulo[$i]["sabtar"] = preencheColunaTurno($sabtar[$i]["periodos"]);

    }

    //faz um calculo para verificar quantas colunas serão impressas,
    $divisor = 0;
    if($cmanha > 0) $divisor++;
    if($ctarde > 0) $divisor++;
    if($cnoite > 0) $divisor++;
    if($csabman > 0) $divisor++;
    if($csabtar > 0) $divisor++;

    //o maximo é 1000px, 220 estão reservados para a coluna do nome da sala
    $colSala = 220;
    $col = (1000-$colSala)/$divisor;

    $html = '
    <style type="text/css">
        @page {	margin: 40px; }                
        p.cabecalho { font-size: 11px; font-family: Calibri;}		     
        p.titulo { font-size: 36px; font-family: Calibri; text-align:center; margin:10px}		     
        p.titulo6 { font-size: 22px; font-family: Calibri; text-align:center; margin:10px}		     
    </style>
    <div style="border: 5; border-style: double;width:1122px; height: 793px;">
        <div id="is_cab">
            <table id="cabecalho" style="margin-left: auto; margin-right: auto;">
                <tbody>
                    <tr>
                        <td><img src="' . BASE_DIR . 'img/unitins_logo_imp.png"></td>
                        <td>&nbsp;</td>
                        <td>
                            <p class="cabecalho">
                                FUNDAÇÃO UNIVERSIDADE DO TOCANTINS – UNITINS<br>
                                PRÓ-REITORIA DE GRADUAÇÃO<br>
                                CÂMPUS GRACIOSA<br>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="is_titulo">
            <p class="titulo"><b>MAPA DE USO DAS SALAS – '.$nomeSemestre.' <br>'.$nomeCurso.'</b></p> 
        </div>
        <div id="is_conteudo">
            <table id="conteudo" border="1" style="margin-left: auto; margin-right: auto; border-style: double;" >
                <tbody>
                    <!-- cabeçalho da tabela -->
                    <tr>
                        <td width="'.$colSala.'px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>SALA/BLOCO</p>
                        </td>
    ';
    //vai verificar quais colunas estão preenchidas
    if($cmanha > 0){
        $html .= '       <td width="'.$col.'px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>2ª À 6ª MATUTINO</b></p>
                        </td>';
    }
    if($ctarde > 0){
        $html .= '       <td width="'.$col.'px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>2ª À 6ª VESPERTINO</b></p>
                        </td>';
    }
    if($cnoite > 0){
        $html .= '       <td width="'.$col.'px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>2ª À 6ª NOTURNO</b></p>
                        </td>';
    }
    if($csabman > 0){
        $html .= '       <td width="'.$col.'px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>SÁBADO MATUTINO</b></p>
                        </td>';
    }
    if($csabtar > 0){
        $html .= '       <td width="'.$col.'px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>SÁBADO VESPERTINO</b></p>
                        </td>';
    }
    $html .= '
                    </tr>
                    <!-- cabeçalho da tabela -->
                    ';
    foreach ($salaTitulo as $idx => $val){
        $html .= '
                    <tr>
                        <td width="'.$colSala.'px" height="60px">
                            <p class="titulo6"><center>'.$val["nome"].'</p>
                        </td>';
        if($cmanha > 0){
            $html .= '  <td width="'.$col.'px" height="60px">
                            <p class="titulo6"><center>'.$val["manha"].'</p>
                        </td>';
        }
        if($ctarde > 0){
            $html .= '  <td width="'.$col.'px" height="60px">
                            <p class="titulo6"><center>'.$val["tarde"].'</p>
                        </td>';
        }
        if($cnoite > 0){
            $html .= '  <td width="'.$col.'px" height="60px">
                            <p class="titulo6"><center>'.$val["noite"].'</p>
                        </td>';
        }
        if($csabman > 0){
            $html .= ' <td width="'.$col.'px" height="60px">
                            <p class="titulo6"><center>'.$val["sabman"].'</p>
                        </td>';
        }
        if($csabtar > 0){
            $html .= '  <td width="'.$col.'px" height="60px">
                            <p class="titulo6"><center>'.$val["sabtar"].'</p>
                        </td>';
        }

        $html .=    '
                    </tr>                
        ';
    }
    $html .= '
                </tbody>
            </table>
        </div>
    </div>
    ';
    return $html;
}

//impressão de laboratorios
function mapaLabim($labs,$nomeCurso, $nomeSemestre,$observacoes){
    //contadores dos turnos
    $cmanha=0;$ctarde=0;$cnoite=0;
    //variavel de impressão
    $labTitulo = [];
    $contLab = 0;
    //
    $cursoid = 0;
    //gera a lista dos labs eliminando as repetições
    foreach ($labs as $idx => $val){
        $cursoid = $val->getOferta()->getCurso()->getId();
        //verifica se tem algum registro duplicado
        if(empty($labTitulo)){
            $labTitulo = getFiltroLabs($labTitulo,$contLab,$val);
            $contLab++;
        }
        else{
            $match = false;
            foreach ($labTitulo as $ipx => $var){
                //verifica se vai dar algum match com outros registros
                if($var["idlab"] == $val->getSala()->getId())
                    $match = true;
            }
            //se for falso, ele adiciona ao array
            if(!$match){
                $labTitulo = getFiltroLabs($labTitulo,$contLab,$val);
                $contLab++;
            }
        }
    }

    //formata os dias conforme o array de labTitulo, para adicionar os periodos
    foreach ($labTitulo as $key => $val){
        $seg[$key] = getFormataDias($val["idlab"]);
        $ter[$key] = getFormataDias($val["idlab"]);
        $qua[$key] = getFormataDias($val["idlab"]);
        $qui[$key] = getFormataDias($val["idlab"]);
        $sex[$key] = getFormataDias($val["idlab"]);
        $sab[$key] = getFormataDias($val["idlab"]);
    }

    //preenche o vetor dos dias com base nos labs
    foreach ($labs as $key => $val){
        $posLab = null;
        //pega o dia da semana e o turno
        $turno = $val->getOferta()->getTurno()->getId();
        $dia = $val->getOferta()->getDiasemana()->getId();
        $id = $val->getSala()->getId();
        //verifica se tem algum registro duplicado
        foreach ($labTitulo as $ipx => $var){
            if($var["idlab"] == $id){
                $posLab = $ipx;
                break;
            }
        }
        //contador para verficar o turno
        if($turno == 9) $cmanha++;
        if($turno == 10) $ctarde++;
        if($turno == 11) $cnoite++;
        //verifica o dia, depois o turno dentro do metodo que será chamado
        //18:sab,17:sex,16:qui,15:qua,14:ter,13:seg
        if($dia == 13)
            $seg[$posLab] = preencheDiasPeriodo($seg[$posLab], $turno, $val);
        if($dia == 14)
            $ter[$posLab] = preencheDiasPeriodo($ter[$posLab], $turno, $val);
        if($dia == 15)
            $qua[$posLab] = preencheDiasPeriodo($qua[$posLab], $turno, $val);
        if($dia == 16)
            $qui[$posLab] = preencheDiasPeriodo($qui[$posLab], $turno, $val);
        if($dia == 17)
            $sex[$posLab] = preencheDiasPeriodo($sex[$posLab], $turno, $val);
        if($dia == 18)
            $sab[$posLab] = preencheDiasPeriodo($sab[$posLab], $turno, $val);

    }

    //preenche os horarios de $labTitulo e preenche a parte de horarios com base nos dias
    for($i=0;$i<$contLab;$i++) {
        //verifica se não vem vazio para chamar e sobrescrever o que já vem na posicao do turno
        $labTitulo[$i]["seg"] = preencherCelulaHorarios($labTitulo[$i]["seg"], $seg[$i]);
        $labTitulo[$i]["ter"] = preencherCelulaHorarios($labTitulo[$i]["ter"], $ter[$i]);
        $labTitulo[$i]["qua"] = preencherCelulaHorarios($labTitulo[$i]["qua"], $qua[$i]);
        $labTitulo[$i]["qui"] = preencherCelulaHorarios($labTitulo[$i]["qui"], $qui[$i]);
        $labTitulo[$i]["sex"] = preencherCelulaHorarios($labTitulo[$i]["sex"], $sex[$i]);
        $labTitulo[$i]["sab"] = preencherCelulaHorarios($labTitulo[$i]["sab"], $sab[$i]);
    }

    //preenche e formata a obs
    $obs = preencherObservacao($observacoes);

    //verifica nas observações se tem alguma monitoria, e encaixa no labTitulos
    $labTitulo = preencherMonitoria($obs,$labTitulo);

    $html = '
        <style type="text/css">
            @page {	margin: 40px; }                
            p.cabecalho { font-size: 11px; font-family: Calibri;}		     
            p.titulo { font-size: 25px; font-family: Calibri; text-align:center; margin:5px}		     
            p.titulo2 { font-size: 15px; font-family: Calibri; text-align:center; margin:5px;}		     
            p.titulo6 { font-size: 15px; font-family: Calibri; text-align:center; margin:5px;}		     
            p.titulo3 { font-size: 15px; font-family: Times; text-align: left; margin:5px;}		     
        </style>
        <div style="border: 5; border-style: double;width:1122px; height: 793px;">
            <div id="is_cab">
                <table id="cabecalho" style="margin-left: auto; margin-right: auto;">
                    <tbody>
                        <tr>
                            <td><img src="' . BASE_DIR . 'img/unitins_logo_imp.png"></td>
                            <td>&nbsp;</td>
                            <td>
                                <p class="cabecalho">
                                    FUNDAÇÃO UNIVERSIDADE DO TOCANTINS – UNITINS<br>
                                    PRÓ-REITORIA DE GRADUAÇÃO<br>
                                    CÂMPUS GRACIOSA<br>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="is_titulo">
                <p class="titulo"><b>MAPA DE USO DOS LABINS  – '.$nomeSemestre.' <br> '.$nomeCurso.'</b></p> 
            </div>
            <div id="is_conteudo" style="">
                <table id="conteudo" border="1" style="margin-left: auto; margin-right: auto; border-style: double;" >
                    <tbody>
                         <!-- cabeçalho da tabela -->                                               
                        <tr>
                            <td width="112px" height="25px" bgcolor="#c0c0c0" >
                                <p class="titulo6"><b><center>SALA</p>
                            </td>
                            <td width="148px" height="25px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SEGUNDA</p>
                            </td>
                             <td width="148px" height="25px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>TERÇA</p>
                            </td>
                             <td width="148px" height="25px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>QUARTA</p>
                            </td>
                             <td width="148px" height="25px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>QUINTA</p>
                            </td>
                             <td width="148px" height="25px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SEXTA</p>
                            </td>
                            <td width="148px" height="25px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SÁBADO</p>
                            </td>                            
                        </tr>
                        <!-- cabeçalho da tabela -->
                        <!-- Conteudo da tabela , possui duas linhas pra cada sala e o turno-->';
    //laço para checar OS TURNOS
    for($i = 9; $i <= 11; $i ++){
        //verfica por turno
        $pass = false;
        $retorno = checaImpTurno($i,$cmanha,$ctarde,$cnoite,$pass);
        $turno = $retorno["texto"];
        $pass = $retorno["check"];
        if($pass){
            $html .='                        
                        <tr>
                            <td width="1000px" height="25px" bgcolor="#c0c0c0" colspan="7">
                                <p class="titulo2"><b><center>'.$turno.'</p>
                            </td>
                        </tr>';
            foreach ($labTitulo as $key => $val){
                //precisa checar se ao menos um horario está no turno
                if(checaImpLab($i,$val)){
                    $html .='    
                        <tr>
                            <td width="100px" height="25px" rowspan="2">
                                <center>'.$val["nome"].'</center>
                            </td>
                            <td width="148px" height="25px">
                                <center>'.$val["seg"][$i]["h1"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["ter"][$i]["h1"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["qua"][$i]["h1"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["qui"][$i]["h1"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["sex"][$i]["h1"].'</center>
                            </td>
                            <td width="148px" height="25px">
                                <center>'.$val["sab"][$i]["h1"].'</center>
                            </td>                            
                        </tr>
                        <tr>
                            <td width="148px" height="25px" >
                                <center>'.$val["seg"][$i]["h2"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["ter"][$i]["h2"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["qua"][$i]["h2"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["qui"][$i]["h2"].'</center>
                            </td>
                             <td width="148px" height="25px">
                                <center>'.$val["sex"][$i]["h2"].'</center>
                            </td>
                            <td width="148px" height="25px">
                                <center>'.$val["sab"][$i]["h2"].'</center>
                            </td>                             
                        </tr>';
                }
            }
        }
    }
    $html .='
                    </tbody>
                </table>
            </div>
            <div id="rodape">';

    if(count($obs)>0){

        $html .= '<p class="titulo3"><b>OBS:</b><br>';
        $pos = 1;
        foreach ($obs as $key => $val){
            if($val["tipouso"] == 20 || $val["tipouso"] == 21){
                $html .= '            
                    <b>'.$pos.' . '.$val["nome"].'</b>: '.$val["obs"].'.<br>
                 ';
                $pos++;
            }
        }
        $html .= ' 
                </p>';
    }
    $html .='               
            </div>
        </div>
    ';
    return $html;
}

/*
 * Metodo que pega as salas por curso
 * @param entityManager, iDsemestre, idCurso, idTipo
 * @return objeto salas
 */
function getSalalab($eM,$semestre,$curso,$tipo){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select("Als, Sa, O, Sl, S")
        ->from('Alocacaosala', "Als")
        ->innerJoin("Als.sala", "Sa")
        ->innerJoin("Als.oferta", "O")
        ->innerJoin("Als.semestre", "Sl")
        ->innerJoin("Sl.semestre", "S")
        ->where('S.id = :semestre')
        ->andWhere('O.curso = :curso')
        ->andWhere('Sa.tipo = :tiposala')
        ->andWhere('Als.id IS NOT NULL')
        ->setParameter('semestre', $semestre)
        ->setParameter('tiposala', $tipo)
        ->setParameter('curso', $curso)
        ->orderBy("Sa.nome", 'ASC');

    return $qb->getQuery()->getResult();
}

/*
 * Metodo que pega as observacoes
 * @param entityManager, iDsemestre, idCurso, idTipo
 * @return objeto obs
 */
function getObservacao($eM,$semestre){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select("La, Tu, T, D, Sa, C, Se")
        ->from('Laboratorio', "La")
        ->innerJoin("La.semestre", "Se")
        ->innerJoin("La.laboratorio", "Sa")
        ->innerJoin("La.tipouso", "Tu")
        ->leftJoin("La.turno", "T")
        ->leftJoin("La.dia", "D")
        ->leftJoin("La.curso", "C")
        ->where('Se.id = :semestre')
        ->setParameter('semestre', $semestre);

    return $qb->getQuery()->getResult();
}

//metodos auxiliares de MAPA SALA
/*
 * Metodo que fltra as salas
 * @param salas, $salaTitulo, $contSala
 * @return array $salaTitulo e $contSala PREENCHIDO
 */
function getFiltroSalas($dados, $salaTitulo, $contSala){
    foreach ($dados as $idx => $val){
        //verifica se tem algum registro duplicado
        if(empty($salaTitulo)){
            $salaTitulo[$contSala]["nome"] = '<p class="titulo6">'.$val->getSala()->getNome().'</p>';
            $salaTitulo[$contSala]["idsala"] = $val->getSala()->getId();
            $salaTitulo[$contSala]["manha"] = '<p class="titulo6">---</p>';
            $salaTitulo[$contSala]["tarde"] = '<p class="titulo6">---</p>';
            $salaTitulo[$contSala]["noite"] = '<p class="titulo6">---</p>';
            $salaTitulo[$contSala]["sabman"] = '<p class="titulo6">---</p>';
            $salaTitulo[$contSala]["sabtar"] = '<p class="titulo6">---</p>';
            $contSala++;
        }
        else{
            $match = false;
            foreach ($salaTitulo as $ipx => $var){
                //verifica se vai dar algum match com outros registros
                if($var["idsala"] == $val->getSala()->getId())
                    $match = true;

            }
            //se for falso, ele adiciona ao array
            if(!$match){
                $salaTitulo[$contSala]["nome"] = '<p class="titulo6">'.$val->getSala()->getNome().'</p>';
                $salaTitulo[$contSala]["idsala"] = $val->getSala()->getId();
                $salaTitulo[$contSala]["manha"] = '<p class="titulo6">---</p>';
                $salaTitulo[$contSala]["tarde"] = '<p class="titulo6">---</p>';
                $salaTitulo[$contSala]["noite"] = '<p class="titulo6">---</p>';
                $salaTitulo[$contSala]["sabman"] = '<p class="titulo6">---</p>';
                $salaTitulo[$contSala]["sabtar"] = '<p class="titulo6">---</p>';
                $contSala++;
            }
        }
    }

    return $retorno = ["array" => $salaTitulo, "contador" => $contSala];
}

/*
 * Metodo que prenche o turno as salas
 * @param array $turno, int per, int $contador
 * @return array $turno[periodos] PREENCHIDO já normatizado
 * $manha[$posSala]["periodos"],$turno,$periodo,$cmanha);
 */
function preencheTurno($dados,$per,$contador){
    //verifica se os dados vem nulos
    $dados[$contador] = $per;
    $resultado = array_unique($dados);
    asort($resultado);
    return $resultado;
}

/*
 * Metodo que prenche os periodos de acordo com os turnos, com base nas salas
 * @param salas, salaTitulo, manha, tarde, noite, sabman, sabtar, cmanha, ctarde, cnoite, csabman, csabtar
 * @return array com todos esses dados - de menos salas e salaTitulo - preenchidos
 */
function preencherPeriodo($salas,$salaTitulo,$manha,$tarde,$noite,$sabman,$sabtar,$cmanha,$ctarde,$cnoite,$csabman,$csabtar){
    //preenche o array dos turnos com os periodos, com base na sala
    foreach ($salas as $idx => $val){
        $posSala = null;
        //pega o dia da semana e o turno
        $turno = $val->getOferta()->getTurno()->getId();
        $dia = $val->getOferta()->getDiasemana()->getId();
        $id = $val->getSala()->getId();
        $periodo = $val->getOferta()->getPeriodo();
        //verifica se tem algum registro duplicado
        foreach ($salaTitulo as $ipx => $var){
            if($var["idsala"] == $id){
                $posSala = $ipx;
                break;
            }
        }
        //manha de segunda a sexta
        if($turno == 9 && $dia != 18){
            //chama função para verificar os periodo
            $manha[$posSala]["periodos"] = preencheTurno($manha[$posSala]["periodos"],$periodo,$cmanha);
            $cmanha++;
        }
        //tarde de segunda a sexta
        if($turno == 10 && $dia != 18){
            $tarde[$posSala]["periodos"] = preencheTurno($tarde[$posSala]["periodos"],$periodo,$ctarde);
            $ctarde++;
        }
        //noite de segunda a sexta
        if($turno == 11 && $dia != 18){
            $noite[$posSala]["periodos"] = preencheTurno($noite[$posSala]["periodos"],$periodo,$cnoite);
            $cnoite++;
        }
        //sabado matutino
        if($dia == 18 && $turno == 9){
            $sabman[$posSala]["periodos"] = preencheTurno($sabman[$posSala]["periodos"],$periodo,$csabman);
            $csabman++;
        }
        //sabado vespertino
        if($dia == 18 && $turno == 10){
            $sabtar[$posSala]["periodos"] = preencheTurno($sabtar[$posSala]["periodos"],$periodo,$csabtar);
            $csabtar++;
        }
    }

    $retorno = [
        "manha" =>$manha,"tarde"=>$tarde,"noite"=>$noite,"sabman"=>$sabman,"sabtar"=>$sabtar,
        "cmanha"=>$cmanha,"ctarde"=>$ctarde,"cnoite"=>$cnoite,"csabman"=>$csabman,"csabtar"=>$csabtar,
    ];

    return $retorno;
}

/*
 * Metodo que prenche na coluna de cada turno das salas os respectivos periodos - só chama se a coluna do turno nao estiver vaiza
 * @param array salaTitulo
 * @return string resultado com a formatação correta
 */
function preencheColunaTurno($salaTitulo){
    $texto = "";
    $pos = 0;
    $aux = [];
    //para ordenar o array
    foreach ($salaTitulo as $idx => $val){
        $aux[$pos] = $val;
        $pos++;
    }
    //revalida
    $salaTitulo = $aux;

    //verifica se possui um registro
    if(count($salaTitulo) == 1){
        //verifica se é regularização ou não
        if($salaTitulo[0] == 0)
            $texto = '<p class="titulo6">REGULARIZAÇÃO</p>';
        else
            $texto = '<p class="titulo6">'.$salaTitulo[0].'º PERÍODO</p>';

    }
    else{
        $total = count($salaTitulo);
        //caso tenha mais de um, faz um laço para preencher tudo
        for ($p=0;$p<$total;$p++){
            //verifica se é regularização
            if($salaTitulo[$p] != 0)
                $texto .= $salaTitulo[$p]."º";
            else
                $texto .= "REG";

            //coloca a barra
            if($p < $total-1)
                $texto .= "/";
        }
        //adiciona o P ao final e gera a string já na formatação
        $texto .= "P";
    }
    return $texto;
}

//metodos auxiliares de MAPA LAB
/*
 * Metodo que fltra as os labs
 * @param $labTitulo, $contLab, $dados
 * @return array $labTitulo
 */
function getFiltroLabs($labTitulo,$contLab,$dados){
    $labTitulo[$contLab]["nome"] = '<p class="titulo2">'.$dados->getSala()->getNome().'</p>';
    $labTitulo[$contLab]["idcurso"] = $dados->getOferta()->getCurso()->getId();
    $labTitulo[$contLab]["idlab"] = $dados->getSala()->getId();
    for($i=9;$i<=11;$i++){
        $labTitulo[$contLab]["seg"][$i]["h1"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["seg"][$i]["h2"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["seg"][$i]["cont"] = 0;
        $labTitulo[$contLab]["ter"][$i]["h1"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["ter"][$i]["h2"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["ter"][$i]["cont"] = 0;
        $labTitulo[$contLab]["qua"][$i]["h1"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["qua"][$i]["h2"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["qua"][$i]["cont"] = 0;
        $labTitulo[$contLab]["qui"][$i]["h1"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["qui"][$i]["h2"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["qui"][$i]["cont"] = 0;
        $labTitulo[$contLab]["sex"][$i]["h1"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["sex"][$i]["h2"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["sex"][$i]["cont"] = 0;
        $labTitulo[$contLab]["sab"][$i]["h1"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["sab"][$i]["h2"] = '<p class="titulo2">---</p>';
        $labTitulo[$contLab]["sab"][$i]["cont"] = 0;
    }
    return $labTitulo;
}

/*
 * Metodo que inicia os dias para o lab
 * @param $labTitulo, $contLab, $dados
 * @return array $dia selecionado FORMATADO
 */
function getFormataDias($id){
    $dia["idlab"] = $id;
    for($i=9;$i<=11;$i++){
        $dia[$i]["h1"] = null;
        $dia[$i]["h2"] = null;
    }
    return $dia;
}

/*
 * Metodo que  preenche os dias com os periodos
 * @param array dia, integer $turno, objeto $lab
 * @return array $dia selecionado preenchido
 */
function preencheDiasPeriodo($dia, $turno, $lab){
    $horario = $lab->getOferta()->getTipohorario();
    $periodo = $lab->getOferta()->getPeriodo();

    $c1 = count($dia[$turno]["h1"]);
    $c2 = count($dia[$turno]["h2"]);

    //1: horario 1, 2:horario 2, 3: turno cheio
    //adiciona no array
    if($horario == 1){
        $dia[$turno]["h1"][$c1] = $periodo;
    }
    if($horario == 2){
        $dia[$turno]["h2"][$c2] = $periodo;
    }
    if($horario == 3) {
        $dia[$turno]["h1"][$c1] = $periodo;
        $dia[$turno]["h2"][$c2] = $periodo;
    }

    //junta valores repetidos
    if(count($dia[$turno]["h1"]) > 0){
        $resultado1 = array_unique($dia[$turno]["h1"]);
        asort($resultado1);
        $dia[$turno]["h1"] = $resultado1;
    }
    if(count($dia[$turno]["h2"]) > 0){
        $resultado2 = array_unique($dia[$turno]["h2"]);
        asort($resultado2);
        $dia[$turno]["h2"] = $resultado2;
    }

    return $dia;
}

/*
 * Metodo que preenche as celulas com os horarios
 * @param array $labTitulo, array $dia
 * @return array $lab PREENCHIDO
 */
function preencherCelulaHorarios($lab,$dia){
    for($i=9;$i<=11;$i++){
        $c1 = count($dia[$i]["h1"]);
        $c2 = count($dia[$i]["h2"]);
        //verifica se está vazio, só assim para modificar os dados
        //parte 1
        if($c1 > 0){
            $texto1 = "";
            //verifica se tem apenas um elemento, se não faz um laçõ
            if($c1 == 1){
                $per = $dia[$i]["h1"][0];
                if($per != 0)
                    $texto1 = '<p class="titulo2">'.$per.'º PERÍODO</p>';
                else
                    $texto1 = '<p class="titulo2">REGULARIZAÇÃO</p>';

                //sobrescreve o que estava escrito anteriormente
                $lab[$i]["h1"] = $texto1;
                $lab[$i]["cont"] = 1;
            }
            else {
                //caso tenha mais de um, faz um laço para preencher tudo
                for ($p=0;$p<$c1;$p++) {
                    //verifica se é regularização
                    if ($dia[$i]["h1"][$p] != 0)
                        $texto1 .= $dia[$i]["h1"][$p] . "º";
                    else
                        $texto1 .= "REG";

                    //coloca a barra
                    if ($p < $c1 - 1)
                        $texto1 .= "/";
                }
            }
            $lab[$i]["h1"] = '<p class="titulo2">'.$texto1.'</p>';
            $lab[$i]["cont"] += 1;
        }
        //parte 2
        if($c2 > 0){
            $texto2 = "";
            //verifica se tem apenas um elemento, se não faz um laçõ
            if($c2 == 1){
                $per = $dia[$i]["h2"][0];
                if($per != 0)
                    $texto2 = '<p class="titulo2">'.$per.'º PERÍODO</p>';
                else
                    $texto2 = '<p class="titulo2">REGULARIZAÇÃO</p>';

                //sobrescreve o que estava escrito anteriormente
                $lab[$i]["h2"] = $texto2;
            }
            else {
                //caso tenha mais de um, faz um laço para preencher tudo
                for ($p=0;$p<$c2;$p++) {
                    //verifica se é regularização
                    if ($dia[$i]["h2"][$p] != 0)
                        $texto2 .= $dia[$i]["h2"][$p] . "º";
                    else
                        $texto2 .= "REG";

                    //coloca a barra
                    if ($p < $c2 - 1)
                        $texto2 .= "/";
                }
            }
            $lab[$i]["h2"] = '<p class="titulo2">'.$texto2.'</p>';
            $lab[$i]["cont"] += 1;
        }
    }
    return $lab;
}

/*
 * Metodo que preenche as observacoes
 * @param array $observacoes
 * @return array $dados PREENCHIDO
 */
function preencherObservacao($obs){
    $dados = [];
    if(count($obs)>0){
        foreach ($obs as $key => $val){
            $dados[$key]["nome"] = $val->getLaboratorio()->getNome();
            $dados[$key]["obs"] = $val->getObservacao();
            $dados[$key]["idlab"] = $val->getLaboratorio()->getId();
            //dados que podem vir nulos
            $dados[$key]["tipouso"] = null;
            $dados[$key]["idcurso"] = null;
            $dados[$key]["dia"] = null;
            $dados[$key]["turno"] = null;
            if(!empty($val->getTipouso()))
                $dados[$key]["tipouso"] = $val->getTipouso()->getId();
            if(!empty($val->getCurso()))
                $dados[$key]["idcurso"] = $val->getCurso()->getId();
            if(!empty($val->getDia()))
                $dados[$key]["dia"] = $val->getDia()->getId();
            if(!empty($val->getTurno()))
                $dados[$key]["turno"] = $val->getTurno()->getId();

        }
    }
    return $dados;
}

/*
 * Metodo que preenche as monitorias nos labs
 * @param array $obs, array $labTitulo
 * @return array $labs
 */
function preencherMonitoria($obs,$labs){
    //percorre os labs para preencher
    for($i=0;$i<count($labs);$i++){
        //percorre as observações para leitura
        foreach ($obs as $key => $val){
            //apenas monitorias
            if($val["tipouso"] == 19){
                //echo 'idcurso: '.$labs[$i]["idcurso"].' e '.$val["idcurso"].', idlab: '.$labs[$i]["idlab"].' e '.$val["idlab"].';<br>';

                if($labs[$i]["idcurso"] == $val["idcurso"] && $val["idlab"] == $labs[$i]["idlab"]){
                    //verifica se os turnos são compativeis
                    for($t=9;$t<=11;$t++){
                        if($val["turno"] == $t){
                            if($val["dia"] == 13){
                                $labs[$i]["seg"][$t]["h1"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["seg"][$t]["h2"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["seg"][$t]["cont"] += 1;
                            }
                            if($val["dia"] == 14){
                                $labs[$i]["ter"][$t]["h1"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["ter"][$t]["h2"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["ter"][$t]["cont"] += 1;
                            }
                            if($val["dia"] == 15){
                                $labs[$i]["qua"][$t]["h1"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["qua"][$t]["h2"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["qua"][$t]["cont"] += 1;
                            }
                            if($val["dia"] == 16){
                                $labs[$i]["qui"][$t]["h1"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["qui"][$t]["h2"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["qui"][$t]["cont"] += 1;
                            }
                            if($val["dia"] == 17){
                                $labs[$i]["sex"][$t]["h1"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["sex"][$t]["h2"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["sex"][$t]["cont"] += 1;
                            }
                            if($val["dia"] == 18){
                                $labs[$i]["sab"][$t]["h1"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["sab"][$t]["h2"] = '<p class="titulo2">MONITORIA</p>';
                                $labs[$i]["sab"][$t]["cont"] += 1;
                            }
                            break;
                        }
                    }
                }
            }
        }
    }
    return $labs;
}

/*
 * Metodo que verifica se o turno esta preenchido
 * @param turno, cmanha, ctarde, cnoite, pass
 * @return array com texto e pass
 */
function checaImpTurno($turno, $cmanha, $ctarde, $cnoite, $pass){
    $texto = "";
    if($turno == 9 && $cmanha > 0){
        $texto = "MATUTINO";
        $pass = true;
    }
    if($turno == 10 && $ctarde > 0){
        $texto = "VESPERTINO";
        $pass = true;
    }
    if($turno == 11 && $cnoite > 0){
        $texto = "NOTURNO";
        $pass = true;
    }
    return ["texto" => $texto, "check" => $pass];
}

/*
 * Metodo que verifica se tem algum horario no lab verificado
 * @param turno, lab
 * @return boolean com resultado
 */
function checaImpLab($turno, $lab){
    $retorno = false;
    if($lab["seg"][$turno]["cont"]>0) $retorno = true;
    if($lab["ter"][$turno]["cont"]>0) $retorno = true;
    if($lab["qua"][$turno]["cont"]>0) $retorno = true;
    if($lab["qui"][$turno]["cont"]>0) $retorno = true;
    if($lab["sex"][$turno]["cont"]>0) $retorno = true;
    if($lab["sab"][$turno]["cont"]>0) $retorno = true;
    return $retorno;
}