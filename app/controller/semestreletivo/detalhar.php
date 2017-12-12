<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 10/09/2017
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
        $mensagem = "Semestre Letivo não informado";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Semestreletivo', $_POST['id']);
        $data['id'] = $model->getId();
        $data['semestre'] = $model->getSemestre()->getDescricao();
        $data['idsemestre'] = $model->getSemestre()->getId();
        $data['curso'] = $model->getCurso()->getNome();
        $data['idcurso'] = $model->getCurso()->getId();
        $mensagem = "Semestre letivo carregado com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>