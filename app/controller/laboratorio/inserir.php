<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 18/11/2017
 * Arquivo responsavel por inserir um registro novo ou editado ( nesse caso o id é informado )
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

try {
    //parametros capturados via post
    $parametros = [
        'id' => $_POST['id'],
        'laboratorio' => $_POST['lab'],
        'tipouso' => $_POST['tipouso'],
        'turno' => $_POST['turnolab'],
        'dia' => $_POST['dia'],
        'observacao' => $_POST['observacao'],
        'semestre' => $_POST['semestre']
    ];

    //validação dos campos
    if (empty($parametros['semestre']) || empty($parametros['observacao']) || empty($parametros['laboratorio']) ||
        empty($parametros['tipouso'])
    ) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($parametros['semestre'])) {
            $mensagem .= "1 - Semestre  <br>";
        }
        if (empty($parametros['observacao'])) {
            $mensagem .= "2 - Observação  <br>";
        }
        if (empty($parametros['laboratorio'])) {
            $mensagem .= "3 - Laborátorio  <br>";
        }
        if (empty($parametros['tipo'])) {
            $mensagem .= "4 - Tipo de Uso  <br>";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Laboratorio;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Laboratorio', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    $semestre = $entityManager->find('Vwsemestre', $parametros['semestre']);
    $laboratorio = $entityManager->find('Sala', $parametros['laboratorio']);
    $tipouso = $entityManager->find('Registro', $parametros['tipouso']);
    $dia = $entityManager->find('Registro', $parametros['dia']);
    $turno = $entityManager->find('Registro', $parametros['turno']);

    //seta os parametros corretamente
    $model->setObservacao($parametros['observacao']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    $model->setSemestre($semestre);
    $model->setLaboratorio($laboratorio);
    $model->setTipouso($tipouso);
    $model->setTurno($turno);
    $model->setDia($dia);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>