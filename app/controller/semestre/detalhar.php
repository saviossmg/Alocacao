<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 07/08/2017
 * Arquivo responsavel por detalhar um registro para exibição ou para edição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

try {
    //checa a requisição
    if (empty($_POST['id'])) {
        $mensagem = "Semestre não informado";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Vwsemestre', $_POST['id']);
        $data['id'] = $model->getId();
        $data['descricao'] = $model->getDescricao();
        $data['datainicio'] = date_format($model->getDatainicio(), 'd/m/Y');
        $data['datafim'] = date_format($model->getDatafim(), 'd/m/Y');

        $mensagem = "Semestre carregado com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>