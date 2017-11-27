<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/09/2017
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
        $mensagem = "Turno/Horário não informado";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Turnohorarios', $_POST['id']);
        $data['id'] = $model->getId();
        $data['turno'] = $model->getTurno()->getId();
        $data['horaainicio'] = $model->getHoraainicio();
        $data['horaafim'] = $model->getHoraafim();
        $data['horabinicio'] = $model->getHorabinicio();
        $data['horabfim'] = $model->getHorabfim();

        $mensagem = "Turno/Horário carregado com Sucesso!";

        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>