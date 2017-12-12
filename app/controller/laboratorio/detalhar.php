<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 18/11/2017
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
        $mensagem = "Laboratorio não informado";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Laboratorio', $_POST['id']);
        $data['id'] = $model->getId();
        $data['lab'] = $model->getLaboratorio()->getId();
        $data['tipouso'] = $model->getTipouso()->getId();
        $data['observacao'] = $model->getObservacao();
        $data['turno'] = "";
        $data['dia'] = "";

        if(!empty($model->getTurno())){
            $data['turno'] = $model->getTurno()->getId();
        }

        if(!empty($model->getDia())){
            $data['dia'] = $model->getDia()->getId();
        }


        $mensagem = "Laborátorio carregado com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);

?>