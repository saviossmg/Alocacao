<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 09/09/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = "";

//cria a query builder
$qb = $entityManager->createQueryBuilder();
    $qb->select("c")
        ->from('Vwcurso', "c")
        ->where("c.unidade IS NOT NULL")
        ->andWhere("c.id IS NOT NULL ");


$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(c.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    foreach ($rs as $idx => $model) {
        $data .= '
        <option  data-tokens="'.$model->getNome().'" value='.$model->getId().'>'.$model->getNome().'</option>
        ';
    }
} else {
    // records now found
    $data = "";
}

echo $data;

?>
