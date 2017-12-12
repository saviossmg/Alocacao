<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 23/09/2017
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
        'turno' => $_POST['turno'],
        'horaainicio' => $_POST['horaainicio'],
        'horaafim' => $_POST['horaafim'],
        'horabinicio' => $_POST['horabinicio'],
        'horabfim' => $_POST['horabfim']
    ];

    //validação dos campos
    if (empty($parametros['turno']) || empty($parametros['horaainicio']) || empty($parametros['horaafim'])
        || empty($parametros['horabinicio']) || empty($parametros['horabfim'])) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br> ";
        if (empty($parametros['turno'])) {
            $mensagem .= "1 - Turno <br>";
        }
        if (empty($parametros['horaainicio'])) {
            $mensagem .= "2 - Horário 1 - Início <br>";
        }
        if (empty($parametros['horaafim'])) {
            $mensagem .= "3 - Horário 1 - Fim <br>";
        }
        if (empty($parametros['horabinicio'])) {
            $mensagem .= "4 - Horário 2 - Início <br>";
        }
        if (empty($parametros['horabfim'])) {
            $mensagem .= "5 - Horário 2 - Fim <br>";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //verifica se já existe um turno cadastrado
        $turno = $entityManager->getRepository('Turnohorarios')->findBy(array('turno' =>  $parametros['turno']));
        if (!empty($turno)) {
            $mensagem = "6 - Já há um horário cadastrado para esse Turno!<br>";
            throw new Exception($mensagem);
        }
        else{
            //instancia novo model
            $model = new Turnohorarios;
            $mensagem = "Registro inserido com SUCESSO!";
        }

    } //atualizar
    else {
        $model = $entityManager->find('Turnohorarios', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    //seta os parametros corretamente
    $model->setTurno($entityManager->find('Registro', $parametros['turno']));
    $model->setHoraainicio($parametros['horaainicio']);
    $model->setHorabinicio($parametros['horabinicio']);
    $model->setHoraafim($parametros['horaafim']);
    $model->setHorabfim($parametros['horabfim']);

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>