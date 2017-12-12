<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 30/05/2017
 * Arquivo responsavel por detalhar um registro para exibição ou para edição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

try {
    //checa a requisição
    if (empty($_POST['id'])) {
        $mensagem = "Predio não informado";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Predio', $_POST['id']);
        $data['id'] = $model->getId();
        $data['nome'] = $model->getNome();
        $data['pisos'] = $model->getPisos();
        if(!empty($model->getUnidade()) ? $data['unidade'] =  $model->getUnidade()->getId(): $data['unidade'] =  0);
        if(($model->getAtivo()) ? $data['ativo'] = 1: $data['ativo'] = 0);

        $mensagem = "Predio carregado com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>