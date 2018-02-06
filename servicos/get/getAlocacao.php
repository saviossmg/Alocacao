<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 21/09/2017
 * Arquivo responsavel pela listagem de todas as alocações para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
include 'getHash.php';

 
$data = [];
$data2 = [];

try{
    if(empty($_GET)){
        $mensagem = "HASH não informado";
        throw new Exception($mensagem);
    }
    else
    if(sha1($_GET["hash"]) != $hash) {
        $mensagem = "HASH incorreto!";
        throw new Exception($mensagem);
    }
    else{
        //cria a query builder
        $qb = $entityManager->createQueryBuilder();
        $qb->select("a, sl, sa, o, se, c, t, d")
            ->from('Alocacaosala', "a")
            ->leftJoin("a.semestre", "sl")
            ->leftJoin("a.sala", "sa")
            ->leftJoin("a.oferta", "o")
            ->leftJoin("o.curso", "c")
            ->leftJoin("o.turno", "t")
            ->leftJoin("o.diasemana", "d")
            ->leftJoin("sl.semestre", "se")
            ->where("a.id IS NOT NULL");

        //parametros capturados via get
        if(!empty($_GET['curso'])){
            $qb->andWhere("c.id = :curso")
                ->setParameter('curso', $_GET['curso']);
        }
        if(!empty($_GET['semestre'])){
            $qb->andWhere("se.id = :semestre")
                ->setParameter('semestre', $_GET['semestre']);
        }
        if(!empty($_GET['periodo'])){
            $qb->andWhere("o.periodo = :periodo")
                ->setParameter('periodo', $_GET['periodo']);
        }
        if(!empty($_GET['sala'])){
            $qb->andWhere("sa.id = :sala")
                ->setParameter('sala', $_GET['sala']);
        }
        if(!empty($_GET['turno'])){
            $qb->andWhere("t.id = :turno")
                ->setParameter('turno', $_GET['turno']);
        }
        if(!empty($_GET['dia'])){
            $qb->andWhere("d.id = :dia")
                ->setParameter('dia', $_GET['dia']);
        }

        $qb->orderBy("a.id",'ASC');
        $rs = $qb->getQuery()->getResult();

        //contador de registros
        $qCount = clone $qb;
        $qCount->select("count(o.id)");
        $totalregistro = $qCount->getQuery()->getSingleScalarResult();

        if ($totalregistro > 0) {
            foreach ($rs as $idx => $model) {
                $data[$idx]["semestre"] = $model->getSemestre()->getId();
                $data[$idx]["sala"] = $model->getSala()->getId();
                $data[$idx]["oferta"] = $model->getOferta()->getId();

                //procura a oferta
                $ofer = $entityManager->find('Oferta',$model->getOferta()->getId());
                $data2[$idx]["id"] = $ofer->getId();
                $data2[$idx]["nometurma"] = $ofer->getNometurma();
                $data2[$idx]["curso"] = $ofer->getCurso()->getId();
                $data2[$idx]["diasemana"] = $ofer->getDiasemana()->getDescricao();
                $data2[$idx]["periodo"] = $ofer->getPeriodo();
                $data2[$idx]["disciplina"] = $ofer->getDisciplina();
                $data2[$idx]["descricaoperiodoletivo"] = $ofer->getDescricaoperiodoletivo();
                $data2[$idx]["horainiciala"] = $ofer->getHorainiciala();
                $data2[$idx]["horafinala"] = $ofer->getHorafinala();
                $data2[$idx]["intervaloinicio"] = $ofer->getIntervaloinicio();
                $data2[$idx]["intervalofim"] = $ofer->getIntervalofinal();
                $data2[$idx]["horainicialb"] = $ofer->getHorainicialb();
                $data2[$idx]["horafinalb"] = $ofer->getHorafinalb();
                $data2[$idx]["professor"] = $ofer->getProfessortitular();
                $data2[$idx]["tipohorario"] = $ofer->getTipohorario();
                $data2[$idx]["turno"] = null;
                if(!empty($ofer->getTurno()))
                    $data2[$idx]["turno"] = $ofer->getTurno()->getDescricao();
            }
            $mensagem =  $totalregistro." registros encontrados";
            $resultado = ['status' => true, 'mensagem' => $mensagem, 'aloc' => $data, 'ofer' => $data2 ];
        } else {
            // records now found
            $mensagem = "Nenhum registro foi encontrado.";
            $resultado = ['status' => false, 'mensagem' => $mensagem, 'aloc' => null, 'ofer' => null];
        }
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: ".$ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'aloc' => null, 'ofer' => null];
}
$retorno = json_encode($resultado);

echo $retorno;
 
?>