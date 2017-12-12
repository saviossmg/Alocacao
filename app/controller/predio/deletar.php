<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 30/05/2017
 * Arquivo responsavel por deletar um registro
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];

try {
    if (isset($_POST['id']) && isset($_POST['id']) != "") {
        // get user id
        $id = $_POST['id'];
        $model = $entityManager->find('Predio', $id);


        $entityManager->remove($model);
        $entityManager->flush();

        $resultado = [
            'status' => true,
            'mensagem' => "Registro Excluiddo com Sucesso!",
            'data' => null
        ];
    }
    else{
        $resultado = [
            'status' => false,
            'mensagem' => "Atenção: Predio não informado",
            'data' => null
        ];
    }
} catch (Exception $ex) {
    $resultado = [
        'status' => false,
        'mensagem' => "Atenção: ".$ex->getMessage(),
        'data' => null
    ];
}

echo json_encode($resultado);