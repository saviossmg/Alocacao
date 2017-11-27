<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/05/2017
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
        'identidade' => $_POST['identidade'],
        'descricao' => $_POST['descricao'],
        'ativo' => $_POST['ativo']
    ];

    //validação dos campos
    if (empty($parametros['identidade']) || empty($parametros['descricao'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['nome'])) {
            $mensagem .= "1 - Entidade  ";
        }
        if (empty($parametros['endereco'])) {
            $mensagem .= "2 - Descrição  ";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Registro();
        $entidade = $entityManager->find('Entidade', $parametros['identidade']);
        $model->setIdentidade($entidade);
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Registro', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //seta os parametros corretamente
    $model->setDescricao($parametros['descricao']);
    $model->setAtivo($parametros['ativo']);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

