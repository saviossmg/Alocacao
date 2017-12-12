<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 30/05/2017
 * Arquivo responsavel por inserir um registro novo ou editado ( nesse caso o id é informado )
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem = "";

try {
    //parametros capturados via post
    $parametros = [
        'id' => $_POST['id'],
        'nome' => $_POST['nome'],
        'pisos' => $_POST['pisos'],
        'unidade' => $_POST['unidade'],
        'ativo' => $_POST['ativo']
    ];

    //validação dos campos
    if (empty($parametros['nome']) || empty($parametros['pisos']))
    {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['nome'])) {
            $mensagem .= "1 - Nome  ";
        }
        if (empty($parametros['pisos'])) {
            $mensagem .= "2 - Pisos  ";
        }
        throw new Exception($mensagem);
    }

    //verificação dos campos
    if (!empty($parametros['pisos']) && !is_numeric($parametros['pisos'])) {
        $mensagem .= "2 - O campo PISOS deve ser númerico ";
        throw new Exception($mensagem);
    }

    //salvar
    $model = null;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Predio;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Predio', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //busca o administrador o diretor geral
    $unidade = $entityManager->find('Unidade', $parametros['unidade']);

    //seta os parametros corretamente
    $model->setNome($parametros['nome']);
    $model->setPisos($parametros['pisos']);
    $model->setAtivo($parametros['ativo']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    if(!empty($unidade) ? $model->setUnidade($unidade): $model->setUnidade(null));

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);