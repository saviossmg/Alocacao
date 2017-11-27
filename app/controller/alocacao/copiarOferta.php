<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/09/2017
 * Arquivo responsavel por copiar uma oferta e duplica-la
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];

try {
    if (isset($_POST['id']) && isset($_POST['id']) != "") {
        // get user id
        $id = $_POST['id'];
        $oferta = $entityManager->find('Oferta', $id);

        $model = new Oferta;
        $model->setCodperiodoletivo($oferta->getCodperiodoletivo());
        $model->setCodturma($oferta->getCodturma());
        $model->setNometurma($oferta->getNometurma());
        $model->setContexto($oferta->getContexto());
        $model->setCurso($oferta->getCurso());
        $model->setDiasemana($oferta->getDiasemana());
        $model->setPeriodo($oferta->getPeriodo());
        $model->setDisciplina($oferta->getDisciplina());
        $model->setDescricaoperiodoletivo($oferta->getDescricaoperiodoletivo());
        $model->setTurno(null);
        $model->setTipoprofessor($oferta->getTipoprofessor());
        $model->setProfessortitular($oferta->getProfessortitular());


        $entityManager->persist($model);
        $entityManager->flush();

        $resultado = [
            'status' => true,
            'mensagem' => "Registro duplicado com Sucesso!",
            'data' => null
        ];
    }
    else{
        $resultado = [
            'status' => false,
            'mensagem' => "Atenção: Oferta não informada",
            'data' => null
        ];
    }
} catch (Exception $ex) {
    $resultado = [
        'status' => false,
        'mensagem' => "Atenção: ".$ex->getMessage(),
        'data' => null
    ];
}

echo json_encode($resultado);

?>