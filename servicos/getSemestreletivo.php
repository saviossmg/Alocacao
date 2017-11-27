<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 17/10/2017
 * Arquivo responsavel pela listagem de todos os semestres letivos para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

$mensagem;
$data = [];

try {
    $qb = $entityManager->createQueryBuilder();
    $qb->select("c")
        ->from('Semestreletivo', "c")
        ->andWhere("c.id IS NOT NULL ");

    $rs = $qb->getQuery()->getResult();
    //contador de registros
    $qCount = clone $qb;
    $qCount->select("count(c.id)");
    $totalregistro = $qCount->getQuery()->getSingleScalarResult();

    if ($totalregistro > 0) {
        foreach ($rs as $idx => $model) {
            $data[$idx]["id"] = $model->getId();
            $data[$idx]["semestre"] = $model->getSemestre()->getId();
            $data[$idx]["curso"] = $model->getCurso()->getId();
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