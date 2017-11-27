<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 01/06/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = "";
$vazio = $_POST['vazio'];

//cria a query builder
$qb = $entityManager->createQueryBuilder();
if($vazio==0){
    $qb->select("s")
        ->from('Sala', "s")
        ->andWhere("s.id IS NOT NULL ");
}
else{
    $qb->select("s")
        ->from('Sala', "s")
        ->where("s.predio IS NULL")
        ->andWhere("s.id IS NOT NULL ");
}
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