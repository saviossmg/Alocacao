<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 05/11/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$unidade = $_POST['unidade'];

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("s, P, U")
        ->from('Sala', "s")
        ->leftJoin("s.predio", "P")
        ->leftJoin("P.unidade", "U")
        ->where("U.id = :unidade ")
        ->andWhere("s.id IS NOT NULL ")
        ->setParameter('unidade', $unidade);

$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(s.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

$data = [];
$retorno = [];

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    foreach ($rs as $idx => $model) {
            //$data .= '<option value="' . $model->getId() . '">' . $model->getNome() . ' - '.$model->getPredio()->getNome(). '</option>';
        $data[$idx]["valor"] = $model->getId();
        $data[$idx]["descricao"] = $model->getNome()." - ".$model->getPredio()->getNome();
    }
    $retorno = ['status' => true, 'data' => $data];
} else {
    // records now found
    $retorno = ['status' => false, 'data' => null];
}

echo json_encode($retorno);

?>