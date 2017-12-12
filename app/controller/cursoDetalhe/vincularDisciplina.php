<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 25/06/2017
 * Arquivo responsavel por vincular um registro a uma entidade
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

$_POST['disciplina'] = 2;
$_POST['idCurso'] = 1;

try {
    //parametros capturados via post
    $parametros = [
        'id' => $_POST['disciplina'],
        'curso' => $_POST['idCurso']
    ];
    //validação dos campos
    if (empty($parametros['id']) || empty($parametros['curso']))
    {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['id'])) {
            $mensagem .= "1 - Disciplina  ";
        }
        if (empty($parametros['curso'])) {
            $mensagem .= "2 - Curso  ";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model = $entityManager->find('Vwdisciplina', $parametros['id']);
    if(empty($model->getCurso())){
        $mensagem = "Registro vinculado com SUCESSO!";
        //busca o administrador o diretor geral
        $curso = $entityManager->find('Vwcurso', $parametros['curso']);

        //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
        $model->setCurso($curso);

        $entityManager->persist($model);
        $entityManager->flush();

        $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
    }
    else{
        $mensagem = "Sala já é vinculada a um prédio.";
        throw new Exception($mensagem);
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);