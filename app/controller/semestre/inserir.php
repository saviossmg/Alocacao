<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 07/09/2017
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
        'descricao' => $_POST['descricao'],
        'datainicio' => $_POST['datainicio'],
        'datafim' => $_POST['datafim']
    ];


    //validação dos campos
    if (empty($parametros['descricao']) || empty($parametros['datainicio']) || empty($parametros['datafim'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br/>";
        if (empty($parametros['descricao'])) {
            $mensagem .= "1 - Descrição  <br/>";
        }
        if (empty($parametros['datainicio'])) {
            $mensagem .= "2 - Data de ínicio  <br/>";
        }
        if (empty($parametros['datafim'])) {
            $mensagem .= "3 - Data de Término <br/> ";
        }
        throw new Exception($mensagem);
    }

    $datai = explode ('/', $parametros['datainicio']);
    $dataf = explode ('/', $parametros['datafim']);


    $datai = $datai[2].'-'.$datai[1].'-'.$datai[0];
    $dataf = $dataf[2].'-'.$dataf[1].'-'.$dataf[0];

    $auxDataI = \date("Y-m-d", strtotime($datai));
    $auxDataF = \date("Y-m-d", strtotime($dataf));


    //verificação dos campos
    if($auxDataF <= $auxDataI){
        $mensagem .= "Data de Término do semestre deve ser maior que a data de ínicio.";
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Vwsemestre();
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Vwsemestre', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //seta os parametros corretamente
    $model->setDescricao($parametros['descricao']);
    $model->setDatainicio(new DateTime($auxDataI));
    $model->setDatafim(new DateTime($auxDataF));

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

