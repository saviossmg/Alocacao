<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 04/11/2017
 * Arquivo responsavel por imprimir o relatorio de...
 */
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

$qb = $entityManager->createQueryBuilder();
$qb->select("Als, Sa, O, Sl, S")
    ->from('Alocacaosala', "Als")
    ->innerJoin("Als.sala", "Sa")
    ->innerJoin("Als.oferta", "O")
    ->innerJoin("Als.semestre", "Sl")
    ->innerJoin("Sl.semestre", "S")
    ->where('S.id = :semestre')
    ->andWhere('Sa.id = :sala')
    ->andWhere('Als.id IS NOT NULL')
    ->setParameter('semestre', $semestre->getId())
    ->setParameter('sala', $model->getId());
$rs = $qb->getQuery()->getResult();

echo count($rs);
