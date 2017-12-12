<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 24/06/2017
 * Arquivo responsavel por inserir um registro novo ou editado ( nesse caso o id é informado )
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

try {
    //parametros capturados via post
    $parametros = [
        'id' => $_POST['id'],
        'registro' => $_POST['registro'],
        'sala' => $_POST['sala'],
        'quantidade' => $_POST['quantidade']
    ];

    //var_dump(json_encode($parametros));die();

    //validação dos campos
    if (empty($parametros['registro']) || empty($parametros['sala']) || empty($parametros['quantidade'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['registro'])) {
            $mensagem .= "1 - Tipo de Equipamento  ";
        }
        if (empty($parametros['sala'])) {
            $mensagem .= "2 - Sala  ";
        }
        if (empty($parametros['quantidade'])) {
            $mensagem .= "3 - Quantidade  ";
        }
        throw new Exception($mensagem);
    }

    //verificação dos campos
    if (!empty($parametros['quantidade']) && !is_numeric($parametros['quantidade'])) {
        $mensagem .= "3 - Quantidade deve ser númerico ";
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Recursossalas();
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Recursossalas', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //busca o administrador o diretor geral
    $registro = $entityManager->find('Registro', $parametros['registro']);
    $sala = $entityManager->find('Sala', $parametros['sala']);

    //seta os parametros corretamente
    $model->setQuantidade($parametros['quantidade']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    $model->setRegistro($registro);
    $model->setSala($sala);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

