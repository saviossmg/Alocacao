<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 17/10/2017
 * Arquivo responsavel pela listagem de todas as ofertas para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

try{

    //cria a query builder
    $qb = $entityManager->createQueryBuilder();
    $qb->select("o, c, t, ds")
        ->from('Oferta', "o")
        ->leftJoin("o.curso", "c")
        ->leftJoin("o.diasemana", "ds")
        ->leftJoin("o.turno", "t")
        ->andWhere("o.id IS NOT NULL");
    $qb->orderBy("o.id",'ASC');
    $rs = $qb->getQuery()->getResult();
    //contador de registros
    $qCount = clone $qb;
    $qCount->select("count(o.id)");
    $totalregistro = $qCount->getQuery()->getSingleScalarResult();

    if ($totalregistro > 0) {
        foreach ($rs as $idx => $model) {
            $data[$idx]["id"] = $model->getId();
            $data[$idx]["nometurma"] = $model->getNometurma();
            $data[$idx]["curso"] = $model->getCurso()->getId();
            $data[$idx]["diasemana"] = $model->getDiasemana()->getDescricao();
            $data[$idx]["periodo"] = $model->getPeriodo();
            $data[$idx]["disciplina"] = $model->getDisciplina();
            $data[$idx]["descricaoperiodoletivo"] = $model->getDescricaoperiodoletivo();
            $data[$idx]["horainiciala"] = $model->getHorainiciala();
            $data[$idx]["horafinala"] = $model->getHorafinala();
            $data[$idx]["intervaloinicio"] = $model->getIntervaloinicio();
            $data[$idx]["intervalofim"] = $model->getIntervalofinal();
            $data[$idx]["horainicialb"] = $model->getHorainicialb();
            $data[$idx]["horafinalb"] = $model->getHorafinalb();
            $data[$idx]["professor"] = $model->getProfessortitular();
            $data[$idx]["tipohorario"] = $model->getTipohorario();
			$data[$idx]["turno"] = null;
			if(!empty($model->getTurno()))
				$data[$idx]["turno"] = $model->getTurno()->getDescricao();	

        }
        $mensagem =  $totalregistro." registros encontrados";
        $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $data];
    } else {
        // records now found
        $mensagem = "Nenhum registro foi encontrado.";
        $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: ".$ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}
$retorno = json_encode($resultado);

echo $retorno;

?>