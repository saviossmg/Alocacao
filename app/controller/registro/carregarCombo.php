<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/05/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = "";
$identidade = $_POST['identidade'];
$idpai = $_POST['idpai'];

//cria a query builder
$qb = $entityManager->createQueryBuilder();
if(empty($idpai)){
    $qb->select("r")
        ->from('Registro', "r")
        ->where('r.ativo = :ativo')
        ->andWhere('r.identidade = :identidade')
        ->andWhere('r.idpai IS NULL')
        ->andWhere("r.id IS NOT NULL ")
        ->setParameter('ativo', 1)
        ->setParameter('identidade', $identidade);
}
else{
    $qb->select("r")
        ->from('Registro', "r")
        ->where('r.ativo = :ativo')
        ->andWhere('r.identidade = :identidade')
        ->andWhere('r.idpai IS NOT NULL')
        ->andWhere("r.id IS NOT NULL ")
        ->setParameter('ativo', 1)
        ->setParameter('identidade', $identidade);
}

$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(r.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    foreach ($rs as $idx => $model) {
        $data .= '
        <option value="' . $model->getId() . '">' . $model->getDescricao() . '</option>
        ';
    }
} else {
    // records now found
    $data = "";
}

echo $data;

?>
