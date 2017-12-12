<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 05/12/2017
 * Arquivo responsavel por imprimir o relatorio de mapa de salas
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

        //se tiver salas, ele imprime o relatorio com as salas
        if(count($salas) > 0)
            $htmlx = mapaSala($salas,$model->getNome(),$semestre->getDescricao());

        //se tiver labs, ele imprime
        if(count($labs) > 0)
            $htmlx .= mapaLabim($labs,$model->getNome(),$semestre->getDescricao());

    }


    $mpdf->WriteHTML($htmlx);

    $arquivo .= "MAPA_USO_CURSO.pdf";
    $filename = BASE_DIR . $arquivo;

    $mpdf->Output($filename, 'F');

    $mensagem = "Arquivos impresso com sucesso!";
    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $arquivo];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

//Impressao
function mapaSala($salas,$nomeCurso, $nomeSemestre){
    //turnos
    $manha = 0;
    $tarde = 0;
    $noite = 0;
    $sab = 0;

    $sala = [];

    //salas
    foreach ($salas as $idx => $val){
        //pega o dia da semana e o turno
        $turno = $val->getOferta()->getTurno()->getId();
        $dia = $val->getOferta()->getDiasemana()->getId();

        //nome da sala
        $sala[$idx] = '<p class="titulo6">'.$val->getSala()->getNome().'</p>';

        //manha de segunda a sexta
        if($turno == 9 && $dia != 18){

        }
        //tarde de segunda a sexta
        if($turno == 10 && $dia != 18){

        }
        //noite de segunda a sexta
        if($turno == 11 && $dia != 18){

        }

        //sabado matutino
        if($dia == 18 && $turno == 9){

        }

    }

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
                        <td width="280px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>SALA/BLOCO</p>
                        </td>
                        <td width="180px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>2ª À 6ª MATUTINO</b></p>
                        </td>
                        <td width="180px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>2ª À 6ª VESPERTINO</b></p>
                        </td>
                        <td width="180px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>2ª À 6ª NOTURNO</b></p>
                        </td>
                        <td width="180px" height="60px" bgcolor="#c0c0c0">
                            <p class="titulo6"><b><center>SÁBADO  MATUTINO</b></p>
                        </td>
                    </tr>
                    <!-- cabeçalho da tabela -->
                    ';
    foreach ($sala as $idx => $val){
        $html .= '
                    <tr>
                        <td width="280px" height="60px">
                            <p class="titulo6">'.$val.'</p>
                        </td>
                        <td width="180px" height="60px">
                            <p class="titulo6"><center>periodos</p>
                        </td>
                        <td width="180px" height="60px">
                            <p class="titulo6"><center>periodo</p>
                        </td>
                        <td width="180px" height="60px">
                            <p class="titulo6"><center>periodos</p>
                        </td>
                        <td width="180px" height="60px">
                            <p class="titulo6"><center>periodos</p>
                        </td>
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

function mapaLabim($labs,$nomeCurso, $nomeSemestre){
    $html = '
        <style type="text/css">
            @page {	margin: 40px; }                
            p.cabecalho { font-size: 11px; font-family: Calibri;}		     
            p.titulo { font-size: 36px; font-family: Calibri; text-align:center; margin:10px}		     
            p.titulo2 { font-size: 15px; font-family: Calibri; text-align:center; margin:10px;}		     
            p.titulo3 { font-size: 15px; font-family: Times; text-align: left; margin:10px;}		     
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
                            <td width="1000px" height="30px" bgcolor="#c0c0c0" colspan="7">
                                <p class="titulo2"><b><center>TURNO</p>
                            </td>
                        </tr>                        
                        <tr>
                            <td width="100px" height="30px" bgcolor="#c0c0c0" >
                                <p class="titulo2"><b><center>SALA</p>
                            </td>
                            <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SEGUNDA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>TERÇA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>QUARTA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>QUINTA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SEXTA</p>
                            </td>
                            <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SÁB/MANHÃ</p>
                            </td>                            
                        </tr>
                        <!-- cabeçalho da tabela -->
                        <!-- Conteudo da tabela , possui duas linhas pra cada sala-->
                        <tr>
                            <td width="100px" height="30px" rowspan="2">
                                <p class="titulo2"><center>LAB 3</p>
                            </td>
                            <td width="150px" height="30px" >
                                <p class="titulo2"><center>HORARIO 1</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"></p><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                            <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>                            
                        </tr>
                        <tr>
                            <td width="150px" height="30px" >
                                <p class="titulo2"><center>HORARIO 2</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"></p><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                            <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>                            
                        </tr>
        
                    </tbody>
                </table>
            </div>
            <div id="rodape">
                <p class="titulo3"><b>OBS:</b><br>
                    <b>1.</b> Numeração - nome da sala -  observação que se deseja. Vai puxar das exceções dos laboratorios.
                </p> 
            </div>
        </div>
    ';
    return $html;
}

//metodos auxiliares
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
        ->setParameter('curso', $curso);
    $salas = $qb->getQuery()->getResult();

    return $salas;
}

/*

//Impressao
function mapaSala($salas){
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
                <p class="titulo"><b>MAPA DE USO DAS SALAS – semestre <br> curso</b></p>
            </div>
            <div id="is_conteudo">
                <table id="conteudo" border="1" style="margin-left: auto; margin-right: auto; border-style: double;" >
                    <tbody>
                        <!-- cabeçalho da tabela -->
                        <tr>
                            <td width="280px" height="60px" bgcolor="#c0c0c0">
                                <p class="titulo6"><b><center>SALA/BLOCO</p>
                            </td>
                            <td width="180px" height="60px" bgcolor="#c0c0c0">
                                <p class="titulo6"><b><center>2ª À 6ª MATUTINO</b></p>
                            </td>
                            <td width="180px" height="60px" bgcolor="#c0c0c0">
                                <p class="titulo6"><b><center>2ª À 6ª VESPERTINO</b></p>
                            </td>
                            <td width="180px" height="60px" bgcolor="#c0c0c0">
                                <p class="titulo6"><b><center>2ª À 6ª NOTURNO</b></p>
                            </td>
                            <td width="180px" height="60px" bgcolor="#c0c0c0">
                                <p class="titulo6"><b><center>SÁBADO  MATUTINO</b></p>
                            </td>
                        </tr>
                        <!-- cabeçalho da tabela -->
                        <tr>
                            <td width="280px" height="60px">
                                <p class="titulo6">nome da sala</p>
                            </td>
                            <td width="180px" height="60px">
                                <p class="titulo6"><center>periodos</p>
                            </td>
                            <td width="180px" height="60px">
                                <p class="titulo6"><center>periodo</p>
                            </td>
                            <td width="180px" height="60px">
                                <p class="titulo6"><center>periodos</p>
                            </td>
                            <td width="180px" height="60px">
                                <p class="titulo6"><center>periodos</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    ';
    return $html;
}

function mapaLabim($labs){
    $html = '
        <style type="text/css">
            @page {	margin: 40px; }
            p.cabecalho { font-size: 11px; font-family: Calibri;}
            p.titulo { font-size: 36px; font-family: Calibri; text-align:center; margin:10px}
            p.titulo2 { font-size: 15px; font-family: Calibri; text-align:center; margin:10px;}
            p.titulo3 { font-size: 15px; font-family: Times; text-align: left; margin:10px;}
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
                <p class="titulo"><b>MAPA DE USO DOS LABINS  – semestre <br> curso</b></p>
            </div>
            <div id="is_conteudo" style="">
                <table id="conteudo" border="1" style="margin-left: auto; margin-right: auto; border-style: double;" >
                    <tbody>
                         <!-- cabeçalho da tabela -->
                        <tr>
                            <td width="1000px" height="30px" bgcolor="#c0c0c0" colspan="7">
                                <p class="titulo2"><b><center>TURNO</p>
                            </td>
                        </tr>
                        <tr>
                            <td width="100px" height="30px" bgcolor="#c0c0c0" >
                                <p class="titulo2"><b><center>SALA</p>
                            </td>
                            <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SEGUNDA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>TERÇA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>QUARTA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>QUINTA</p>
                            </td>
                             <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SEXTA</p>
                            </td>
                            <td width="150px" height="30px" bgcolor="#c0c0c0">
                                <p class="titulo2"><b><center>SÁB/MANHÃ</p>
                            </td>
                        </tr>
                        <!-- cabeçalho da tabela -->
                        <!-- Conteudo da tabela , possui duas linhas pra cada sala-->
                        <tr>
                            <td width="100px" height="30px" rowspan="2">
                                <p class="titulo2"><center>LAB 3</p>
                            </td>
                            <td width="150px" height="30px" >
                                <p class="titulo2"><center>HORARIO 1</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"></p><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                            <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px" height="30px" >
                                <p class="titulo2"><center>HORARIO 2</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"></p><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                             <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                            <td width="150px" height="30px">
                                <p class="titulo2"><center>----</p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div id="rodape">
                <p class="titulo3"><b>OBS:</b><br>
                    <b>1.</b> Numeração - nome da sala -  observação que se deseja. Vai puxar das exceções dos laboratorios.
                </p>
            </div>
        </div>
    ';
    return $html;
}

 */