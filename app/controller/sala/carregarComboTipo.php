<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 18/11/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = "";
$tipo = $_POST['tipo'];

//cria a query builder
$qb = $entityManager->createQueryBuilder();
    $qb->select("s, t")
        ->from('Sala', "s")
        ->innerJoin('s.tipo', "t")
        ->where("t.id = :tipoP")
        ->andWhere("s.id IS NOT NULL ")
        ->setParameter("tipoP",$tipo);

$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(s.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    foreach ($rs as $idx => $model) {
        $data .= '
        <option value="' . $model->getId() . '">' . $model->getNome() . ' - '.$model->getPredio()->getNome(). '</option>
        ';
    }
} else {
    // records now found
    $data = "";
}

echo $data;

?>