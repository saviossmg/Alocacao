<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 22/06/2017
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
        $mensagem = "Sala não informada.";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Sala', $_POST['id']);
        $data['id'] = $model->getId();
        $data['nome'] = $model->getNome();
        $data['piso'] = $model->getPiso();
        $data['tipo'] = $model->getTipo()->getId();
        if(!empty($model->getPredio()) ? $data['predio'] =  $model->getPredio()->getId(): $data['predio'] =  0);
        if(($model->getAtivo()) ? $data['ativo'] = 1: $data['ativo'] = 0);

        if(!empty( $model->getPredio())){
            $data['bloco'] = $data['nome']." - ".$model->getPredio()->getNome();
        }
        else{
            $data['bloco'] = $data['nome'];
        }
        $mensagem = "Sala carregada com Sucesso!";

        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json

echo json_encode($resultado);

?>