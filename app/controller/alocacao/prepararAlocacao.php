<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 17/09/2017
 * Arquivo responsavel por preparar os dados para a alocação.
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

try {
    //checa a requisição
    if (empty($_POST['curso']) || empty($_POST['semestre']) || empty($_POST['oferta']) || empty($_POST)) {
        $mensagem = "Dados não foram informados: <br>";
        if(empty($_POST['curso'])){
            $mensagem .= "- Curso <br>";
        }
        if(empty($_POST['semestre'])){
            $mensagem .= "- Semestre <br>";
        }
        if(empty($_POST['oferta'])){
            $mensagem .= "- Oferta <br>";
        }
        $resultado =[ 'status' => false, 'mensagem' => $mensagem ,'data' => null];
    } else {
        $oferta = $entityManager->find('Oferta', $_POST['oferta']);
        $curso =  $entityManager->find('Vwcurso', $_POST['curso']);
        $semestre =  $entityManager->find('Vwsemestre', $_POST['semestre']);
        $data['oferta'] = $oferta->getId();
        $data['disciplina'] = $oferta->getDisciplina();
        $data['periodo'] = $oferta->getPeriodo();
        $data['diasemana'] = $oferta->getDiasemana()->getId();

        $data['semestre'] = $semestre->getDescricao();

        $mensagem = "Dados carregados com Sucesso!";
        $resultado = [ 'status' => true,'mensagem' => $mensagem,'data' => $data ];

    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = [ 'status' => false,'mensagem' => $mensagem, 'data' => null ];
}

//retorna o json
echo json_encode($resultado);


?>