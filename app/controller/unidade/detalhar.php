<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/05/2017
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
        $mensagem = "Unidade não informada";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Unidade', $_POST['id']);
        $data['id'] = $model->getId();
        $data['nome'] = $model->getNome();
        $data['endereco'] = $model->getEndereco();
        $data['cep'] = $model->getCep();
        $data['latitude'] = $model->getLatitude();
        $data['longitude'] = $model->getLongitude();
        $data['administrador'] = $model->getAdministrador()->getId();
        $data['diretorgeral'] = $model->getDiretorgeral()->getId();
        if(($model->getAtivo()) ? $data['ativo'] = 1: $data['ativo'] = 0);
        $mensagem = "Unidade carregada com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>