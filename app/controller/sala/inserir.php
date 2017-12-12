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
$mensagem = "";

try {
    //parametros capturados via post
    $parametros = [
        'id' => $_POST['id'],
        'nome' => $_POST['nome'],
        'piso' => $_POST['piso'],
        'tiposala' => $_POST['tiposala'],
        'predio' => $_POST['predio'],
        'ativo' => $_POST['ativo']
    ];

    //validação dos campos
    if (empty($parametros['nome']) || empty($parametros['piso']) || empty($parametros['tiposala'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['nome'])) {
            $mensagem .= "1 - Nome  ";
        }
        if (empty($parametros['piso'])) {
            $mensagem .= "2 - Piso  ";
        }
        if (empty($parametros['tiposala'])) {
            $mensagem .= "3 - Tipo de Sala  ";
        }
        throw new Exception($mensagem);
    }

    //verificação dos campos
    if (!empty($parametros['piso']) && !is_numeric($parametros['piso'])) {
        $mensagem .= "2 - Piso deve ser númerico ";
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Sala;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Sala', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //busca o administrador o diretor geral
    $tiposala = $entityManager->find('Registro', $parametros['tiposala']);
    $predio = $entityManager->find('Predio', $parametros['predio']);

    //seta os parametros corretamente
    $model->setNome($parametros['nome']);
    $model->setPiso($parametros['piso']);
    $model->setAtivo($parametros['ativo']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    if(!empty($predio) ? $model->setPredio($predio): $model->setPredio(null));

    $model->setTipo($tiposala);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

