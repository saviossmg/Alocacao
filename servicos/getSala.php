<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 17/10/2017
 * Arquivo responsavel pela listagem de todas as salas para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
include 'getHash.php';

$mensagem;
$data = [];

try {
    if(empty($_POST)){
        $mensagem = "HASH não informado";
        throw new Exception($mensagem);
    }
    else
    if(sha1($_POST["hash"]) != $hash) {
        $mensagem = "HASH incorreto!";
        throw new Exception($mensagem);
    }
    else{
        $qb = $entityManager->createQueryBuilder();
        $qb->select("c, u, d")
            ->from('Sala', "c")
            ->leftJoin("c.predio", "u")
            ->leftJoin("c.tipo", "d")
            ->andWhere("c.id IS NOT NULL ");

        $rs = $qb->getQuery()->getResult();
        //contador de registros
        $qCount = clone $qb;
        $qCount->select("count(c.id)");
        $totalregistro = $qCount->getQuery()->getSingleScalarResult();

        if ($totalregistro > 0) {
            foreach ($rs as $idx => $model) {
                $data[$idx]["id"] = $model->getId();
                $data[$idx]["nome"] = $model->getNome();
                $data[$idx]["piso"] = $model->getPiso();
                $data[$idx]["idpredio"] = null;
				if(!empty($model->getPredio()))
					$data[$idx]["idpredio"] = $model->getPredio()->getId();
                $data[$idx]["tipo"] = $model->getTipo()->getDescricao();
                $data[$idx]["ativo"] = $model->getAtivo();
            }
            $mensagem =  $totalregistro." registros encontrados";
            $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $data];
        } else {
            // records now found
            $mensagem = "Nenhum registro foi encontrado.";
            $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
        }
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: ".$ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}
$retorno = json_encode($resultado);

echo $retorno;
?>