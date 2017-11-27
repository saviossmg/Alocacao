<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 09/06/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

//funções necessárias para tratamento do retorno do webservice
function getDiasemana($diaSemana)
{
    //verifica qual dia da semana, de acordo com a chave do banco
    if (strcasecmp($diaSemana, 'Segunda-Feira') == 0) {
        $dia = 13;
    }
    if (strcasecmp($diaSemana, 'Terça-Feira') == 0) {
        $dia = 14;
    }
    if (strcasecmp($diaSemana, 'Quarta-Feira') == 0) {
        $dia = 15;
    }
    if (strcasecmp($diaSemana, 'Quinta-Feira') == 0) {
        $dia = 16;
    }
    if (strcasecmp($diaSemana, 'Sexta-Feira') == 0) {
        $dia = 17;
    }
    if (strcasecmp($diaSemana, 'Sábado') == 0) {
        $dia = 18;
    }
    return $dia;
}

//função para eliminar os registros repetidos
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

//função para ordenar um array de acordo com uma chave
function aasort(&$array, $key)
{
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
    return $array;
}

function getWeb($perLetivo, $curso)
{
    $periodo = str_replace("/","",$perLetivo->getDescricao());
    //URL's do serviço de oferta
    $url = 'https://www.unitins.br/webapi/api/oferta/?periodoLetivo=' . $periodo .
        '&curso=' . $curso->getNome() . '&campus=CÂMPUS%20PALMAS&tipoCurso=1';

    $url = str_replace(" ", "%20", $url);

    //criação do header
    $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

    //download do conteudo do webservice
    $xml = file_get_contents($url, false, $context);

    $horarios = json_decode(json_encode(simplexml_load_string($xml)), 1);

    //elimina os dados
    $detalhes = unique_multidim_array($horarios['Oferta'], 'nomeDisciplina');

    //normatização do array
    $ofertas = [];
    foreach ($detalhes as $model) {
        $periodo = substr($model['nomeTurma'], 0, 2);
        $ofertas[] = [
            'codPeriodoLetivo' => wordwrap($model['codPeriodoLetivo'], 4, '/', true),
            'codTurma' => $model['codTurma'],
            'nomeTurma' => $model['nomeTurma'],
            'contexto' => $model['contexto'],
            'curso' => $model['curso'],
            'diaSemana' => getDiasemana($model['diaSemana']),
            'turno' => null,
            'periodo' => intval($periodo),
            'disciplina' => $model['nomeDisciplina'],
            'descricaoPeriodoLetivo' => $model['descricaoPeriodoLetivo'],
            'horaInicialA' => null,
            'horaInicialB' => null,
            'intervaloInicio' => null,
            'horaFinalA' => null,
            'horaFinalB' => null,
            'intervaloFinal' => null,
            'professorTitular' => $model['professorTitular'],
            'tipoProfessor' => $model['tipoProfessor'],
            'sala' => null
        ];
    }

    //ordena o array
    $ofertas = aasort($ofertas, "periodo");
    $ofertas = aasort($ofertas, "diaSemana");

    return $ofertas;
}

//insere novos registros
function  retornaTotal($entityManager, $curso, $semestre, $att = NULL){
    $qb = $entityManager->createQueryBuilder();
    $qb->select("o, c, ds, t")
        ->from('Oferta', "o")
        ->leftJoin("o.curso", "c")
        ->leftJoin("o.diasemana", "ds")
        ->leftJoin("o.turno", "t")
        ->where("c.id = :cursoP")
        ->andWhere("o.codperiodoletivo LIKE :semestreP")
        ->andWhere("o.id IS NOT NULL ")
        ->setParameter("cursoP", $curso->getId())
        ->setParameter("semestreP", $semestre->getDescricao());

    //verifica o nome da disciplina
    if(!empty($att)){
        $qb->andWhere("o.disciplina LIKE :disciplinaP")
            ->setParameter("disciplinaP", $att);
    }
    $qb->orderBy("o.periodo", 'ASC');
    $rs = $qb->getQuery()->getResult();

    //contador de registros
    $qCount = clone $qb;
    $qCount->select("count(o.id)");
    $contador = $qCount->getQuery()->getSingleScalarResult();

    $retorno = [ 'total' => $contador,'rs' => $rs ];

    //pega o total de registros
    return $retorno;
}

//insere novos registros
function  inserirNovoRegistro($ofertaP,$entityManager, $curso, $semestre){
    $diasemana = $entityManager->find('Registro',$ofertaP['diaSemana']);
    $model = new Oferta;
    //p´rimeiro os dados externos
    $model->setCodperiodoletivo($semestre->getDescricao());
    $model->setCurso($curso);
    $model->setDiasemana($diasemana);
    //
    $model->setCodturma($ofertaP['codTurma']);
    $model->setNometurma($ofertaP['nomeTurma']);
    $model->setContexto($ofertaP['contexto']);
    $model->setPeriodo($ofertaP['periodo']);
    $model->setDisciplina($ofertaP['disciplina']);
    $model->setDescricaoperiodoletivo($ofertaP['descricaoPeriodoLetivo']);
    $model->setProfessortitular($ofertaP['professorTitular']);
    $model->setTipoprofessor($ofertaP['tipoProfessor']);
    $entityManager->persist($model);
    $entityManager->flush();
}

//edita um registro existente
function  editaRegistro($model, $ofertaP,$entityManager){
    $editou = false;
    //pega o modelo que passou no parametro
    if(strcmp($model->getCodturma(),$ofertaP['codTurma'])!=0 || strcmp($model->getProfessortitular(),$ofertaP['professorTitular'])!= 0
    || $model->getDiasemana()->getId() != $ofertaP['diaSemana']){
        $editou = true;

        //ele edita, mesmo que nao mude nada para evitar comparadores desnecessarios
        $diasemana = $entityManager->find('Registro',$ofertaP['diaSemana']);

        $model->setCodturma($ofertaP['codTurma']);
        $model->setProfessortitular($ofertaP['professorTitular']);
        $model->setDiasemana($diasemana);

        $entityManager->persist($model);
        $entityManager->flush();

    }

    return $editou;
}

//FIM DAS FUNÇÕES

//variavel para mostrar o resultado final
$resultado = [];

$mensagem = "";
$inseridos = 0;
$atualizados = 0;

try {
    //carrega os modelos
    $curso = $entityManager->find('Vwcurso', $_POST['idCurso']);
    $semestre = $entityManager->find('Vwsemestre', $_POST['idSemestre']);

    //procura os registros no banco
    //pega o total de registros
    $ret = retornaTotal($entityManager, $curso, $semestre);
    $totalregistro = $ret['total'];
    $oferta = getWeb($semestre, $curso);

    if($totalregistro == 0){
        foreach ($oferta as $idx => $insercao) {
            inserirNovoRegistro($insercao, $entityManager, $curso, $semestre);
            $inseridos++;
        }
    }
    else{
        //verifica os registros
        // Edita APENAS o dia da semana e o professor
        foreach ($oferta as $idx => $insercao) {
            //procura por SEMESTRE LETIVO, CURSO E DISCIPLINA IGUAIS
            //pega o total de registros
            $retorno = retornaTotal($entityManager, $curso, $semestre, $insercao['disciplina']);
            if($retorno['total'] == 0) {
                //passa como parametro o objeto novo
                inserirNovoRegistro($insercao, $entityManager, $curso, $semestre);
                $inseridos++;
            }
            else{
                $edicao = editaRegistro($retorno['rs'][0],$insercao, $entityManager);
                if($edicao){
                    $atualizados++;
                }
            }
        }

    }

    //mensagens e filtros para atualizar/inserir
    $mensagem = "Discplinas carregadas com Sucesso!";
    $mensagem .= "<br>".$inseridos." Ofertas Inseridas!";
    $mensagem .= "<br>".$atualizados." Ofertas Atualizadas!";

    $resultado = [
        'status' => true,
        'mensagem' => $mensagem,
        'data' => null
    ];
} catch (Exception $ex) {
    $resultado = [
        'status' => false,
        'mensagem' => "Atenção: " . $ex->getMessage(),
        'data' => null
    ];
}

echo json_encode($resultado);

?>
