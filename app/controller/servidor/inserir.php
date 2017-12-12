<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/05/2017
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
        'nome' => $_POST['nome'],
        'matricula' => $_POST['matricula'],
        'cargo' => $_POST['cargo'],
        'ativo' => $_POST['ativo'],
    ];

    //validação dos campos
    if (empty($parametros['nome']) || empty($parametros['matricula']) || empty($parametros['cargo'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['nome'])) {
            $mensagem .= "1 - Nome  ";
        }
        if (empty($parametros['matricula'])) {
            $mensagem .= "2 - Matricula  ";
        }
        if (empty($parametros['cargo'])) {
            $mensagem .= "3 - Cargo  ";
        }
        throw new Exception($mensagem);
    }
    //verificação dos campos
    if (!empty($parametros['matricula']) && !is_numeric($parametros['matricula'])) {
        $mensagem .= "2 - Matricula deve ser númerica ";
        throw new Exception($mensagem);
    }
    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new VwServidor;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('VwServidor', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //seta os parametros corretamente
    $model->setNome($parametros['nome']);
    $model->setMatricula($parametros['matricula']);
    $model->setCargo($parametros['cargo']);
    $model->setAtivo($parametros['ativo']);
    $model->setDocente(0);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

