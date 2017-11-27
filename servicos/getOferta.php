<?php
/**
 * Criado por: Sávio Martins Valentim
 * Data: 17/10/2017
 * Arquivo responsavel pela listagem de todas as ofertas para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
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
//            $data[$idx]["semestre"] = $model->getSemestre()->getId();
//            $data[$idx]["sala"] = $model->getSala()->getId();
//            $data[$idx]["oferta"] = $model->getOferta()->getId();
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