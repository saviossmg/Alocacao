<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 03/06/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = "";

//cria a query builder
$qb = $entityManager->createQueryBuilder();
if(empty($_POST['ativo'])){
    $qb->select("s")
        ->from('Vwservidor', "s")
        ->andWhere("s.id IS NOT NULL ");
}
else{
    $qb->select("s")
        ->from('Vwservidor', "s")
        ->where('s.ativo = :ativo')
        ->andWhere("s.id IS NOT NULL ")
        ->setParameter('ativo', 1);

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
        <option value="' . $model->getId() . '">' . $model->getNome() . '</option>
        ';
    }
} else {
    // records now found
    $data = "";
}

echo $data;

?>