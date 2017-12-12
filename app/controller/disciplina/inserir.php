<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 09/06/2017
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
        'codcurricular' => $_POST['codcurricular'],
        'periodo' => $_POST['periodo'],
        'curso' => $_POST['curso'],
        'prerequisito' => $_POST['prerequisito']
    ];

    //validação dos campos
    if (empty($parametros['descricao']) || empty($parametros['codcurricular']) || empty($parametros['periodo']) ) {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['descricao'])) {
            $mensagem .= "1 - Nome  ";
        }
        if (empty($parametros['codcurricular'])) {
            $mensagem .= "2 - Código Curricular  ";
        }
        if (empty($parametros['periodo'])) {
            $mensagem .= "3 - Periodo  ";
        }
        throw new Exception($mensagem);
    }

    //verificação dos campos
    if (!empty($parametros['codcurricular']) && !is_numeric($parametros['codcurricular'])) {
        $mensagem .= "2 - Código Curricular deve ser númerico ";
        throw new Exception($mensagem);
    }

    if (!empty($parametros['periodo']) && !is_numeric($parametros['periodo'])) {
        $mensagem .= "3 - Periodo deve ser númerico ";
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Vwdisciplina;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Vwdisciplina', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //busca o administrador o diretor geral
    $curso = $entityManager->find('Vwcurso', $parametros['curso']);
    $requisito = $entityManager->find('Vwdisciplina', $parametros['prerequisito']);

    //seta os parametros corretamente
    $model->setDescricao($parametros['descricao']);
    $model->setCodcurricular($parametros['codcurricular']);
    $model->setPeriodo($parametros['periodo']);

    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    if(empty($requisito)){
        $model->setPrerequesito(null);
    } else {
        $model->setPrerequesito($requisito);
    }
    if(empty($curso)){
        $model->setCurso(null);
    } else {
        $model->setCurso($curso);
    }

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

