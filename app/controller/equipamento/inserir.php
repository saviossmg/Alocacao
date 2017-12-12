<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 22/06/2017
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
        'descricao' => $_POST['descricao'],
        'identidade' => $_POST['identidade'],
        'idpai' => $_POST['idpai'],
        'ativo' => $_POST['ativo']
    ];

    //validação dos campos
    if (empty($parametros['identidade']) || empty($parametros['descricao']) ||  empty($parametros['idpai'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['identidade'])) {
            $mensagem .= "1 - Entidade  ";
        }
        if (empty($parametros['descricao'])) {
            $mensagem .= "2 - Descrição  ";
        }
        if (empty($parametros['idpai'])) {
            $mensagem .= "3 - Tipo de Equipamento  ";
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
    $idpai = $entityManager->find('Registro', $parametros['idpai']);
    $model->setIdpai($idpai);

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

