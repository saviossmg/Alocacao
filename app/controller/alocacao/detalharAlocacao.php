<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 06/06/2017
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
        $mensagem = "Alocação não informada.";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Alocacaosala', $_POST['id']);
        $data['id'] = $model->getId();
        $data['oferta'] = $model->getOferta()->getId();
        $data['semestre'] = $model->getSemestre()->getSemestre()->getDescricao();
        $data['disciplina'] = $model->getOferta()->getDisciplina();
        $data['diasemana'] = $model->getOferta()->getDiasemana()->getId();
        $data['turno'] = $model->getOferta()->getTurno()->getId();
        $data['sala'] = $model->getSala()->getId();

        $mensagem = "Alocação carregada com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>