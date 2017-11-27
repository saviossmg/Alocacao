<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 06/06/2017
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
        $mensagem = "Curso não informado";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Vwcurso', $_POST['id']);
        $data['id'] = $model->getId();
        $data['nome'] = $model->getNome();
        $data['sigla'] = $model->getSigla();
        $data['codigo'] = $model->getCodcurso();
        $data['unidade'] = $model->getUnidade()->getId();
        $mensagem = "Curso carregado com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>