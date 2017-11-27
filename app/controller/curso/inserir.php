<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 06/06/2017
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
        'nome' => $_POST['nome'],
        'sigla' => $_POST['sigla'],
        'codigo' => $_POST['codigo'],
        'unidade' => $_POST['unidade']
    ];

    //print_r(json_encode($parametros));die();

    //validação dos campos
    if (empty($parametros['nome']) || empty($parametros['sigla']) || empty($parametros['codigo']) || empty($parametros['unidade'])
    ) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($parametros['nome'])) {
            $mensagem .= "1 - Nome  <br>";
        }
        if (empty($parametros['sigla'])) {
            $mensagem .= "2 - Sigla  <br>";
        }
        if (empty($parametros['codigo'])) {
            $mensagem .= "3 - Código  <br>";
        }
        if (empty($parametros['unidade'])) {
            $mensagem .= "4 - Unidade  <br>";
        }
        throw new Exception($mensagem);
    }

    //verificação dos campos
    if (!empty($parametros['codigo']) && !is_numeric($parametros['codigo'])) {
        $mensagem .= "2 - Código deve ser númerico ";
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Vwcurso;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Vwcurso', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //busca o administrador o diretor geral
    $unidade = $entityManager->find('Unidade', $parametros['unidade']);

    //seta os parametros corretamente
    $model->setNome($parametros['nome']);
    $model->setSigla($parametros['sigla']);
    $model->setCodcurso($parametros['codigo']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    $model->setUnidade($unidade);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

