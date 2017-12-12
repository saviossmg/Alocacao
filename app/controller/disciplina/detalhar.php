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
        $mensagem = "Disciplina não informada";
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $model = $entityManager->find('Vwdisciplina', $_POST['id']);
        $data['id'] = $model->getId();
        $data['descricao'] = $model->getDescricao();
        $data['codcurricular'] = $model->getCodcurricular();
        $data['periodo'] = $model->getPeriodo();
        $data['curso'] = $model->getCurso()->getId();
        $prerquisito =  $model->getPrerequesito();
        if(empty($prerquisito)){
            $data['prerequisito'] = 0;
        }else{
            $data['prerequisito'] = $model->getPrerequesito()->getId();
        }

        $mensagem = "Curso carregado com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];

    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
var_dump(json_encode($resultado));

?>