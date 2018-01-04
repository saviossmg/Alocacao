<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 26/12/2017
 * Arquivo responsavel por imprimir o relatorio de mapa de salas
 */
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
require_once BASE_DIR . 'mpdf/mpdf.php';

$arquivo = "impressao/";

//variavel para mostrar o resultado final
$resultado = [];
$mensagem = "";

try {

    //validação dos campos
    if (empty($_POST['semestre']) || empty($_POST['campus'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($_POST['semestre'])) {
            $mensagem .= "1 - Semestre <br>";
        }
        if (empty($_POST['campus'])) {
            $mensagem .= "2 - Campus <br>";
        }
        throw new Exception($mensagem);
    }

    $htmlx = "";

    //deixa em modo paisagem
    $mpdf = new mPDF('c', 'A4-L');

    //Instancia os objetos
    $semestre = $entityManager->find('Vwsemestre', $_POST['semestre']);
    $campus = $entityManager->find('Unidade', $_POST['campus']);

    //pega os blocos
    $blocos = getBlocos($entityManager,$campus->getId());

    //laço que gerará conforme os blocos
    foreach ($blocos as $key => $bloco){
        //dados que sairão na impressão
        $salasImpressao = getSalalab($entityManager,$campus->getId(),1,$bloco->getId());
        $labsImpressao = getSalalab($entityManager,$campus->getId(),2,$bloco->getId());

        //dados para serem normatizados e jogados p/ impressão
        $salas = getSalalabOferta($entityManager,$semestre->getId(),$campus->getId(),1,$bloco->getId());
        $labs = getSalalabOferta($entityManager,$semestre->getId(),$campus->getId(),2,$bloco->getId());

        //gera o html
        if(count($salas) > 0 || count($labs) > 0){
            $htmlx = gerarHtml($salas,$labs,$salasImpressao,$labsImpressao,$semestre,$campus,$entityManager,$bloco);
            $mpdf->WriteHTML($htmlx);
        }
    }

    $nomesem = str_replace("/", ".", $semestre->getDescricao());
    $arquivo .= "MAPA_USO_SALAS".$campus->getNome()."-".$nomesem.".pdf";
    $filename = BASE_DIR . $arquivo;

    $mpdf->Output($filename, 'F');
    $mensagem = "Arquivos impresso com sucesso!";
    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $arquivo];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

/*
 * Metodo que gerao HTML a partir das informações  passadas
 * @param salas, laboratorios, observações semestre, campus
 * @return string html
 */
function gerarHtml($salas,$laboratorios,$salasCampus,$labsCampus,$semestre,$campus,$eM,$bloco){
    //nome de semestre
    $nomesem = str_replace("/", ".", $semestre->getDescricao());
    $nomebloco = strtoupper($bloco->getNome());

    //vetor que terá os dados salas do campus selecionado
    $impressao = [];
    $totalimp = 0;

    //gera a lista de impressão - adicionando as salas
    if(count($salas) > 0){
        $retorno = filtraSalaLab($salasCampus,$impressao,$totalimp);
        $impressao = $retorno["array"];
        $totalimp = $retorno["contador"];
    }

    //gera a lista de dados dos laboratorios eliminando as repetições
    if(count($laboratorios) > 0){
        $retorno = filtraSalaLab($labsCampus,$impressao,$totalimp);
        $impressao = $retorno["array"];
        $totalimp = $retorno["contador"];
    }

    //
    $dadosImp = [];
    $contDadosImp = 0;

    //gera a lista  de dados das salas
    if(count($salas) > 0){
        $retorno = getFiltroSalas($salas,$dadosImp,$contDadosImp);
        $dadosImp = $retorno["array"];
        $contDadosImp = $retorno["contador"];
    }

    //gera a lista de dados dos laboratorios eliminando as repetições
    if(count($laboratorios) > 0){
        $retorno = getFiltroSalas($laboratorios,$dadosImp,$contDadosImp);
        $dadosImp = $retorno["array"];
        $contDadosImp = $retorno["contador"];
    }

    //juntar os dados no array de impressão, já filtrados ao final p/ a impressão
    $impressao = getImpressão($impressao,$totalimp,$dadosImp,$eM);

    //pega as observações, e caso tenha, as formata para jogar no final do relatorio
    $observacoes = getObservacao($eM,$semestre->getId(),$campus->getId(),$bloco->getId());
    $obs = [];
    if(count($observacoes) > 0)
        $obs = preencherObservacao($observacoes);


    $html = '
    <style type="text/css">
        @page {	margin: 32px; }                
        p.cabecalho { font-size: 11px; font-family: Calibri;}		     
        p.titulo { font-size: 20px; font-family: Calibri; text-align:center; margin:0px}		     
        p.texto1 { font-size: 13px; font-family: Calibri; text-align:center; margin:0px}		     
        p.texto2{ font-size: 10px; font-family: Calibri; text-align:center; margin:0px}		     
        p.texto3{ font-size: 13px; font-family: Times; text-align:left; margin:0px}		     
    </style>
    <div style="border: 5; border-style: double;width:1122px; height: 793px;">
        <!--cabeçalho-->
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
                                '.strtoupper($campus->getNome()).'<br>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--cabeçalho-->
        <!--titulo-->
        <div id="is_titulo">
            <p class="titulo"><b>MAPA DE USO DAS SALAS '.$nomebloco.' – '.$nomesem.'</b></p>
        </div>
        <!--titulo-->
        <!--conteudo-->        
        <div id="is_conteudo">
            <table id="conteudo" border="1" style="margin-left: auto; margin-right: auto; " >
                <tbody>
                    <!-- cabeçalho da tabela -->
                    <tr>
                        <td width="140px" height="32px" bgcolor="#c0c0c0">
                            <p class="texto1"><b><center>SALA</p>
                        </td>
                        <td width="175px" height="32px" bgcolor="#c0c0c0">
                            <p class="texto1"><b><center>2ª À 6ª MATUTINO</b></p>
                        </td>
                         <td width="175px" height="32px" bgcolor="#c0c0c0">
                            <p class="texto1"><b><center>2ª À 6ª VESPERTINO</b></p>
                        </td>
                        <td width="175px" height="32px" bgcolor="#c0c0c0">
                            <p class="texto1"><b><center>2ª À 6ª NOTURNO</b></p>
                        </td>
                        <td width="175px" height="32px" bgcolor="#c0c0c0">
                            <p class="texto1"><b><center>SÁBADO MANHA</b></p>
                        </td>
                        <td width="175px" height="32px" bgcolor="#c0c0c0">
                            <p class="texto1"><b><center>SÁBADO TARDE</b></p>
                        </td>
                    </tr>
                    <!-- cabeçalho da tabela -->';
    foreach ($impressao as $key => $val){
        if($val["ativo"]){
            $html .= '
                <tr>
                        <td width="140px" height="32px">
                            <b><center>'.$val["nome"].'</center></b>
                        </td>
                        <td width="175px" height="32px">
                            <center>'.$val["manha"]["imp"].'</center>
                        </td>
                         <td width="175px" height="32px">
                            <center>'.$val["tarde"]["imp"].'</center>
                        </td>
                        <td width="175px" height="32px">
                            <center>'.$val["noite"]["imp"].'</center>
                        </td>
                        <td width="175px" height="32px">
                            <center>'.$val["sabman"]["imp"].'</center>
                        </td>
                        <td width="175px" height="32px">
                            <center>'.$val["sabtar"]["imp"].'</center>
                        </td>
                    </tr>
            ';
        }
        else{
            $html .= '
                    <tr>
                        <td width="100px" height="32px">
                            <p class="texto1"><center><b>'.$val["nome"].'</p>
                        </td>
                        <td width="175px" height="32px" colspan="5">
                            <p class="texto1"><center>---</p>
                        </td>
                    </tr>
            ';
        }
    }
    $html .='                   
                </tbody>
            </table>
        </div>
        <!--conteudo-->   
        <!--rodapé-->   
        <div id="rodape">    ';

    if(count($obs) > 0){
        $html .= '
            <p class="texto3"><b>OBS:</b><br>';
        foreach ($obs as $key => $val){
            if($val["tipouso"] == 20 || $val["tipouso"] == 21){
                $html .= '            
                    <b>'.$val["pos"].' . '.$val["nome"].'</b>: '.$val["obs"].'.<br>
                 ';
            }
        }
        $html .= '
            </p>';
    }

    $html .='
        </div> 
    </div>';
    //retorna o html formatado
    return $html;
}

/*
 * Metodo que pega as salas do campus
 * @param entityManager, campus
 * @return objeto predio
 */
function getBlocos($eM,$campus){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select('B,U')
        ->from('Predio', 'B')
        ->innerJoin('B.unidade', 'U')
        ->where('U.id = :campus')
        ->setParameter('campus', $campus);

    return $qb->getQuery()->getResult();
}

/*
 * Metodo que pega as salas do campus
 * @param entityManager, campus, idTipo
 * @return objeto salas
 */
function getSalalab($eM,$campus,$tipo,$bloco){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select('S,B,U')
        ->from('Sala', 'S')
        ->innerJoin('S.predio', 'B')
        ->innerJoin('B.unidade', 'U')
        ->where('U.id = :campus')
        ->andWhere('S.tipo = :tipo')
        ->andWhere('B.id = :bloco')
        ->setParameter('campus', $campus)
        ->setParameter('tipo', $tipo)
        ->setParameter('bloco', $bloco);

    return $qb->getQuery()->getResult();
}

/*
 * Metodo que pega as salas com ofertas
 * @param entityManager, idsemestre, campus, idTipo
 * @return objeto salas
 */
function getSalalabOferta($eM,$semestre,$campus,$tipo,$bloco){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select("Als, Sa, O, Sl, S, C, U, B")
        ->from('Alocacaosala', "Als")
        ->innerJoin("Als.sala", "Sa")
        ->innerJoin("Als.oferta", "O")
        ->innerJoin("Als.semestre", "Sl")
        ->innerJoin("Sl.semestre", "S")
        ->innerJoin("Sl.curso", "C")
        ->innerJoin("C.unidade", "U")
        ->innerJoin("Sa.predio", "B")
        ->where('S.id = :semestre')
        ->andWhere('Sa.tipo = :tiposala')
        ->andWhere('U.id = :campus')
        ->andWhere('B.id = :bloco')
        ->andWhere('Als.id IS NOT NULL')
        ->setParameter('semestre', $semestre)
        ->setParameter('tiposala', $tipo)
        ->setParameter('campus', $campus)
        ->setParameter('bloco', $bloco)
        ->orderBy("Sa.nome", 'ASC');

    $salas = $qb->getQuery()->getResult();
    return $salas;
}

/*
 * Metodo que pega as observacoes
 * @param entityManager, idsemestre, idccampus, idTipo
 * @return objeto obs
 */
function getObservacao($eM,$semestre,$campus,$bloco){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select("La, Tu, T, D, Sa, C, Se, B, U")
        ->from('Laboratorio', "La")
        ->innerJoin("La.semestre", "Se")
        ->innerJoin("La.laboratorio", "Sa")
        ->innerJoin("La.tipouso", "Tu")
        ->innerJoin("Sa.predio", "B")
        ->innerJoin("B.unidade", "U")
        ->leftJoin("La.turno", "T")
        ->leftJoin("La.dia", "D")
        ->leftJoin("La.curso", "C")
        ->where('Se.id = :semestre')
        ->andWhere('B.id = :bloco')
        ->andWhere('U.id = :campus')
        ->setParameter('semestre', $semestre)
        ->setParameter('bloco', $bloco)
        ->setParameter('campus', $campus);

    return $qb->getQuery()->getResult();
}

/*
 * Metodo que junta as salas e laboratorios do campus
 * @param dados, salaLab, contSala
 * @return array $salaLab e $contSalalab PREENCHIDO
 */
function filtraSalaLab($dados, $salaLab, $contSalalab){
    foreach ($dados as $key => $val){
            $salaLab[$contSalalab]["nome"] = '<p class="texto1">'.$val->getNome().'</p>';
            $salaLab[$contSalalab]["idsala"] = $val->getId();
            $salaLab[$contSalalab]["bloco"] = $val->getPredio()->getNome();
            $salaLab[$contSalalab]["ativo"] = $val->getAtivo();
            //turnos
            $salaLab[$contSalalab]["manha"]["imp"] = '<p class="texto1">---</p>';
            $salaLab[$contSalalab]["manha"]["total"] = 0;
            $salaLab[$contSalalab]["manha"]["idcurso"] = 0;
            $salaLab[$contSalalab]["manha"]["siglacurso"] = null;
            $salaLab[$contSalalab]["manha"]["periodos"] = [];
            $salaLab[$contSalalab]["manha"]["dias"] = [];
            //
            $salaLab[$contSalalab]["tarde"]["imp"] = '<p class="texto1">---</p>';
            $salaLab[$contSalalab]["tarde"]["total"] = 0;
            $salaLab[$contSalalab]["tarde"]["idcurso"] = 0;
            $salaLab[$contSalalab]["tarde"]["siglacurso"] = null;
            $salaLab[$contSalalab]["tarde"]["periodos"] = [];
            $salaLab[$contSalalab]["tarde"]["dias"] = [];
            //
            $salaLab[$contSalalab]["noite"]["imp"] = '<p class="texto1">---</p>';
            $salaLab[$contSalalab]["noite"]["total"] = 0;
            $salaLab[$contSalalab]["noite"]["idcurso"] = 0;
            $salaLab[$contSalalab]["noite"]["siglacurso"] = null;
            $salaLab[$contSalalab]["noite"]["periodos"] = [];
            $salaLab[$contSalalab]["noite"]["dias"] = [];
            //
            $salaLab[$contSalalab]["sabman"]["imp"] = '<p class="texto1">---</p>';
            $salaLab[$contSalalab]["sabman"]["total"] = 0;
            $salaLab[$contSalalab]["sabman"]["idcurso"] = 0;
            $salaLab[$contSalalab]["sabman"]["siglacurso"] = null;
            $salaLab[$contSalalab]["sabman"]["periodos"] = [];
            $salaLab[$contSalalab]["sabman"]["dias"] = [];
            //
            $salaLab[$contSalalab]["sabtar"]["imp"] = '<p class="texto1">---</p>';
            $salaLab[$contSalalab]["sabtar"]["total"] = 0;
            $salaLab[$contSalalab]["sabtar"]["idcurso"] = 0;
            $salaLab[$contSalalab]["sabtar"]["siglacurso"] = null;
            $salaLab[$contSalalab]["sabtar"]["periodos"] = [];
            $salaLab[$contSalalab]["sabtar"]["dias"] = [];
            //
            $contSalalab++;
    }
    return $retorno = ["array" => $salaLab, "contador" => $contSalalab];
}

/*
 * Metodo que fltra salas e laboratorios
 * @param dados, salaLab, contSala
 * @return array $salaLab e $contSalalab PREENCHIDO
 */
function getFiltroSalas($dados, $salaLab, $contSalalab){
    foreach ($dados as $key => $val){
        $salaLab[$contSalalab]["idsala"] = $val->getSala()->getId();
        $salaLab[$contSalalab]["idcurso"] = $val->getOferta()->getCurso()->getId();
        $salaLab[$contSalalab]["siglacurso"] = $val->getOferta()->getCurso()->getSigla();
        $salaLab[$contSalalab]["turno"] = $val->getOferta()->getTurno()->getId();
        $salaLab[$contSalalab]["dia"] = $val->getOferta()->getDiasemana()->getId();
        $salaLab[$contSalalab]["periodo"] = $val->getOferta()->getPeriodo();
        $contSalalab++;
    }
    return $retorno = ["array" => $salaLab, "contador" => $contSalalab];
}

/*
 * Metodo que gera os dados para impressão final
 * @param
 * @return array $impressão preenchido
 */
function getImpressão($impressão,$cont, $dados, $eM){
    //vai percorrer o array de impressão sala por sala
    for($i = 0; $i < $cont; $i++){
        //faz uma leitura dos dados de impressão
        foreach ($dados as $key => $val){
            //verifica se o id da sala é igual
            if($impressão[$i]["idsala"] == $val["idsala"]){
                //verifica o turno que está presente, a partir dele se pega
                //o dia e o periodo do curso
                if($val["turno"] == 9 && $val["dia"] !=18){
                    $impressão[$i]["manha"]["siglacurso"] = $val["siglacurso"];
                    $impressão[$i]["manha"]["idcurso"] = $val["idcurso"];
                    $impressão[$i]["manha"]["dia"][count($impressão[$i]["manha"]["dia"])] = $val["dia"];
                    $impressão[$i]["manha"]["periodos"][count($impressão[$i]["manha"]["periodos"])] = $val["periodo"];
                }
                if($val["turno"] == 10 && $val["dia"]!=18){
                    $impressão[$i]["tarde"]["siglacurso"] = $val["siglacurso"];
                    $impressão[$i]["tarde"]["idcurso"] = $val["idcurso"];
                    $impressão[$i]["tarde"]["dia"][count($impressão[$i]["tarde"]["dia"])] = $val["dia"];
                    $impressão[$i]["tarde"]["periodos"][count($impressão[$i]["tarde"]["periodos"])] = $val["periodo"];
                }
                if($val["turno"] == 11 && $val["dia"]){
                    $impressão[$i]["noite"]["siglacurso"] = $val["siglacurso"];
                    $impressão[$i]["noite"]["idcurso"] = $val["idcurso"];
                    $impressão[$i]["noite"]["dia"][count($impressão[$i]["noite"]["dia"])] = $val["dia"];
                    $impressão[$i]["noite"]["periodos"][count($impressão[$i]["noite"]["periodos"])] = $val["periodo"];
                }
                if($val["turno"] == 9 && $val["dia"] == 18){
                    $impressão[$i]["sabman"]["siglacurso"] = $val["siglacurso"];
                    $impressão[$i]["sabman"]["idcurso"] = $val["idcurso"];
                    $impressão[$i]["sabman"]["dia"][count($impressão[$i]["sabman"]["dia"])] = $val["dia"];
                    $impressão[$i]["sabman"]["periodos"][count($impressão[$i]["sabman"]["periodos"])] = $val["periodo"];
                }
                if($val["turno"] == 10 && $val["dia"] == 18){
                    $impressão[$i]["sabtar"]["siglacurso"] = $val["siglacurso"];
                    $impressão[$i]["sabtar"]["idcurso"] = $val["idcurso"];
                    $impressão[$i]["sabtar"]["dia"][count($impressão[$i]["sabtar"]["dia"])] = $val["dia"];
                    $impressão[$i]["sabtar"]["periodos"][count($impressão[$i]["sabtar"]["periodos"])] = $val["periodo"];
                }
            }
        }
        //Prepara os textos para impressao
        //ordena os periodos e dias, eliminando possiveis repetições
        //caso nao tenha nada ele deixa passar em branco
        if(count($impressão[$i]["manha"]["dia"]) > 0 && count($impressão[$i]["manha"]["periodos"]) > 0){
            //junta os valores iguais
            $resulDia = array_unique($impressão[$i]["manha"]["dia"]);
            $resulPer = array_unique($impressão[$i]["manha"]["periodos"]);
            //sorteia em ordem crescente
            asort($resulDia);
            asort($resulPer);
            //refaz os arrays para ordenar as chaves
            $impressão[$i]["manha"]["dia"] = [];
            $impressão[$i]["manha"]["periodos"] = [];
            foreach($resulDia as $key => $val)
                $impressão[$i]["manha"]["dia"][count($impressão[$i]["manha"]["dia"])] = $val;

            foreach($resulPer as $key => $val)
                $impressão[$i]["manha"]["periodos"][count($impressão[$i]["manha"]["periodos"])] = $val;

            $texto = '<p class="texto1"><b>'.$impressão[$i]["manha"]["siglacurso"].' ';

            //faz o texto dos periodos
            $quantper = count($impressão[$i]["manha"]["periodos"]);
            foreach($impressão[$i]["manha"]["periodos"] as $key => $val){
                if($val == 0) $texto .= 'REG';
                else $texto .= $val;

                if($key < $quantper - 1) $texto .= ', ';
            }
            $texto.= ' ºP </b></p>';

            //verifica os dias livres
            $dias = $impressão[$i]["manha"]["dia"];
            if(count($dias) < 5){
                $texto .= '<p class="texto2"><i>LIVRE: ';
                $cdia = [];
                for ($p = 13; $p <= 17; $p++) {
                    $tem = false;
                    foreach ($dias as $v) {
                        if ($v == $p)
                            $tem = true;
                    }
                    if (!$tem) {
                        $dia = $eM->find('Registro', $p);
                        $pieces = explode("-", $dia->getDescricao());
                        $cdia[count($cdia)] = strtoupper(substr($pieces[0], 0, 3));
                    }
                }
                foreach ($cdia as $k => $v){
                    $texto .= $v;
                    if($k < count($cdia) - 1)
                        $texto .= ' / ';
                }

                $texto .= '</i></p>';
            }
            $impressão[$i]["manha"]["imp"] = $texto;
        }
        //
        if(count($impressão[$i]["tarde"]["dia"]) > 0 && count($impressão[$i]["tarde"]["periodos"]) > 0){
            //junta os valores iguais
            $resulDia = array_unique($impressão[$i]["tarde"]["dia"]);
            $resulPer = array_unique($impressão[$i]["tarde"]["periodos"]);
            //sorteia em ordem crescente
            asort($resulDia);
            asort($resulPer);
            //refaz os arrays para ordenar as chaves
            $impressão[$i]["tarde"]["dia"] = [];
            $impressão[$i]["tarde"]["periodos"] = [];
            foreach($resulDia as $key => $val)
                $impressão[$i]["tarde"]["dia"][count($impressão[$i]["tarde"]["dia"])] = $val;

            foreach($resulPer as $key => $val)
                $impressão[$i]["tarde"]["periodos"][count($impressão[$i]["tarde"]["periodos"])] = $val;

            $texto = '<p class="texto1"><b>'.$impressão[$i]["tarde"]["siglacurso"].' ';

            //faz o texto dos periodos
            $quantper = count($impressão[$i]["tarde"]["periodos"]);
            foreach($impressão[$i]["tarde"]["periodos"] as $key => $val){
                if($val == 0) $texto .= 'REG';
                else $texto .= $val;

                if($key < $quantper - 1) $texto .= ', ';
            }
            $texto.= ' ºP </b></p>';

            //verifica os dias livres
            $dias = $impressão[$i]["tarde"]["dia"];
            if(count($dias) < 5){
                $texto .= '<p class="texto2"><i>LIVRE: ';
                $cdia = [];
                for ($p = 13; $p <= 17; $p++) {
                    $tem = false;
                    foreach ($dias as $v) {
                        if ($v == $p)
                            $tem = true;
                    }
                    if (!$tem) {
                        $dia = $eM->find('Registro', $p);
                        $pieces = explode("-", $dia->getDescricao());
                        $cdia[count($cdia)] = strtoupper(substr($pieces[0], 0, 3));
                    }
                }
                foreach ($cdia as $k => $v){
                    $texto .= $v;
                    if($k < count($cdia) - 1)
                        $texto .= ' / ';
                }

                $texto .= '</i></p>';
            }
            $impressão[$i]["tarde"]["imp"] = $texto;
        }
        //
        if(count($impressão[$i]["noite"]["dia"]) > 0 && count($impressão[$i]["noite"]["periodos"]) > 0){
            //junta os valores iguais
            $resulDia = array_unique($impressão[$i]["noite"]["dia"]);
            $resulPer = array_unique($impressão[$i]["noite"]["periodos"]);
            //sorteia em ordem crescente
            asort($resulDia);
            asort($resulPer);
            //refaz os arrays para ordenar as chaves
            $impressão[$i]["noite"]["dia"] = [];
            $impressão[$i]["noite"]["periodos"] = [];
            foreach($resulDia as $key => $val)
                $impressão[$i]["noite"]["dia"][count($impressão[$i]["noite"]["dia"])] = $val;

            foreach($resulPer as $key => $val)
                $impressão[$i]["noite"]["periodos"][count($impressão[$i]["noite"]["periodos"])] = $val;

            $texto = '<p class="texto1"><b>'.$impressão[$i]["noite"]["siglacurso"].' ';

            //faz o texto dos periodos
            $quantper = count($impressão[$i]["noite"]["periodos"]);
            foreach($impressão[$i]["noite"]["periodos"] as $key => $val){
                if($val == 0) $texto .= 'REG';
                else $texto .= $val;

                if($key < $quantper - 1) $texto .= ', ';
            }
            $texto.= ' ºP </b></p>';

            //verifica os dias livres
            $dias = $impressão[$i]["noite"]["dia"];
            if(count($dias) < 5){
                $texto .= '<p class="texto2"><i>LIVRE: ';
                $cdia = [];
                for ($p = 13; $p <= 17; $p++) {
                    $tem = false;
                    foreach ($dias as $v) {
                        if ($v == $p)
                            $tem = true;
                    }
                    if (!$tem) {
                        $dia = $eM->find('Registro', $p);
                        $pieces = explode("-", $dia->getDescricao());
                        $cdia[count($cdia)] = strtoupper(substr($pieces[0], 0, 3));
                    }
                }
                foreach ($cdia as $k => $v){
                    $texto .= $v;
                    if($k < count($cdia) - 1)
                        $texto .= ' / ';
                }

                $texto .= '</i></p>';
            }
            $impressão[$i]["noite"]["imp"] = $texto;
        }
        //
        if(count($impressão[$i]["sabman"]["dia"]) > 0 && count($impressão[$i]["sabman"]["periodos"]) > 0){
            //junta os valores iguais
            $resulDia = array_unique($impressão[$i]["sabman"]["dia"]);
            $resulPer = array_unique($impressão[$i]["sabman"]["periodos"]);
            //sorteia em ordem crescente
            asort($resulDia);
            asort($resulPer);
            //refaz os arrays para ordenar as chaves
            $impressão[$i]["sabman"]["dia"] = [];
            $impressão[$i]["sabman"]["periodos"] = [];
            foreach($resulDia as $key => $val)
                $impressão[$i]["sabman"]["dia"][count($impressão[$i]["sabman"]["dia"])] = $val;

            foreach($resulPer as $key => $val)
                $impressão[$i]["sabman"]["periodos"][count($impressão[$i]["sabman"]["periodos"])] = $val;

            $texto = '<p class="texto1"><b>'.$impressão[$i]["sabman"]["siglacurso"].' ';

            //faz o texto dos periodos
            $quantper = count($impressão[$i]["sabman"]["periodos"]);
            foreach($impressão[$i]["sabman"]["periodos"] as $key => $val){
                if($val == 0) $texto .= 'REG';
                else $texto .= $val;

                if($key < $quantper - 1) $texto .= ', ';
            }
            $texto.= ' ºP </b></p>';
            $impressão[$i]["sabman"]["imp"] = $texto;
        }
        //
        if(count($impressão[$i]["sabtar"]["dia"]) > 0 && count($impressão[$i]["sabtar"]["periodos"]) > 0){
            //junta os valores iguais
            $resulDia = array_unique($impressão[$i]["sabtar"]["dia"]);
            $resulPer = array_unique($impressão[$i]["sabtar"]["periodos"]);
            //sorteia em ordem crescente
            asort($resulDia);
            asort($resulPer);
            //refaz os arrays para ordenar as chaves
            $impressão[$i]["sabtar"]["dia"] = [];
            $impressão[$i]["sabtar"]["periodos"] = [];
            foreach($resulDia as $key => $val)
                $impressão[$i]["sabtar"]["dia"][count($impressão[$i]["sabtar"]["dia"])] = $val;

            foreach($resulPer as $key => $val)
                $impressão[$i]["sabtar"]["periodos"][count($impressão[$i]["sabtar"]["periodos"])] = $val;

            $texto = '<p class="texto1"><b>'.$impressão[$i]["sabtar"]["siglacurso"].' ';

            //faz o texto dos periodos
            $quantper = count($impressão[$i]["sabtar"]["periodos"]);
            foreach($impressão[$i]["sabtar"]["periodos"] as $key => $val){
                if($val == 0) $texto .= 'REG';
                else $texto .= $val;

                if($key < $quantper - 1) $texto .= ', ';
            }
            $texto.= ' ºP </b></p>';
            $impressão[$i]["sabtar"]["imp"] = $texto;
        }
    }
    return $impressão;
}

/*
 * Metodo que preenche as observacoes
 * @param array $observacoes
 * @return array $dados PREENCHIDO
 */
function preencherObservacao($obs){
    $dados = [];
    if(count($obs)>0){
        $p = 1;
        foreach ($obs as $key => $val){
            $dados[$key]["nome"] = $val->getLaboratorio()->getNome();
            $dados[$key]["pos"] = $p;
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

            $p++;
        }
    }
    return $dados;
}