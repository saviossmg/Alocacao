<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 24/05/2017
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
        'endereco' => $_POST['endereco'],
        'cep' => $_POST['cep'],
        'latitude' => $_POST['latitude'],
        'longitude' => $_POST['longitude'],
        'diretorgeral' => $_POST['diretorgeral'],
        'administrador' => $_POST['administrador'],
        'ativo' => $_POST['ativo'],
    ];

    //validação dos campos
    if (empty($parametros['nome']) || empty($parametros['endereco']) || empty($parametros['cep']) || empty($parametros['diretorgeral'])
        || empty($parametros['administrador'])
    ) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['nome'])) {
            $mensagem .= "1 - Nome  ";
        }
        if (empty($parametros['endereco'])) {
            $mensagem .= "2 - Endereço  ";
        }
        if (empty($parametros['cep'])) {
            $mensagem .= "3 - CEP  ";
        }
        if (empty($parametros['diretorgeral'])) {
            $mensagem .= "4 - Diretor Geral  ";
        }
        if (empty($parametros['administrador'])) {
            $mensagem .= "5 - Administrador ";
        }
        throw new Exception($mensagem);
    }

    //verificação dos campos
    if (!empty($parametros['cep']) && !is_numeric($parametros['cep'])) {
        $mensagem .= "3 - CEP deve ser númerico ";
        throw new Exception($mensagem);
    }
    if (!empty($parametros['latitude']) && !is_numeric($parametros['latitude'])) {
        $mensagem .= "6 - Latitude deve ser númerico ";
        throw new Exception($mensagem);
    }
    if (!empty($parametros['longitude']) && !is_numeric($parametros['longitude'])) {
        $mensagem .= "7 - longitude deve ser númerico ";
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Unidade;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Unidade', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //busca o administrador o diretor geral
    $administrador = $entityManager->find('VwServidor', $parametros['administrador']);
    $diretorgeral = $entityManager->find('VwServidor', $parametros['diretorgeral']);

    //seta os parametros corretamente
    $model->setNome($parametros['nome']);
    $model->setEndereco($parametros['endereco']);
    $model->setCep($parametros['cep']);
    $model->setLatitude($parametros['latitude']);
    $model->setLongitude($parametros['longitude']);
    $model->setAtivo($parametros['ativo']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    $model->setAdministrador($administrador);
    $model->setDiretorgeral($diretorgeral);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

