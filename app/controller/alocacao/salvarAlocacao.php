<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 21/09/2017
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
        'diasemana' => $_POST['diasemana'],
        'turno' => $_POST['turno'],
        'sala' => $_POST['sala'],
        'horario' => $_POST['horario'],
        'oferta' => $_POST['oferta'],
        'idsemestreletivo' => $_POST['idsemestreletivo']
    ];

    //print_r(json_encode($parametros));die();

    //validação dos campos
    if (empty($parametros['diasemana']) || empty($parametros['turno']) || empty($parametros['sala']) || empty($parametros['horario']))
    {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($parametros['diasemana'])) {
            $mensagem .= "1 - Dia da semana  <br>";
        }
        if (empty($parametros['turno'])) {
            $mensagem .= "2 - Turno  <br>";
        }
        if (empty($parametros['sala'])) {
            $mensagem .= "3 - Sala <br>";
        }
        if (empty($parametros['horario'])) {
            $mensagem .= "4 - Horário  <br>";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Alocacaosala;
        $mensagem = "Registro inserido com SUCESSO!<br>A sala foi ALOCADA!";
    } //atualizar
    else {
        $model = $entityManager->find('Alocacaosala', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!<br>A sala foi ALOCADA!";

    }
    //busca os registros
    $oferta = $entityManager->find('Oferta', $parametros['oferta']);
    $horario = $entityManager->getRepository('Turnohorarios')->findBy(array('turno' =>  $parametros['turno']));


    //1 - turno cheio, 2 - turno jhorario 1, 3 - turno horario 2 e se nao passar nada joga full
    if($parametros['horario'] == 1){
        $oferta->setHorainiciala($horario[0]->getHoraainicio());
        $oferta->setHorafinala($horario[0]->getHoraafim());
        $oferta->setHorainicialb($horario[0]->getHorabinicio());
        $oferta->setHorafinalb($horario[0]->getHorabfim());
        $oferta->setIntervaloinicio($horario[0]->getHoraafim());
        $oferta->setIntervalofinal($horario[0]->getHorabinicio());
    }
    else
    if ($parametros['horario'] == 2){
        $oferta->setHorainiciala($horario[0]->getHoraainicio());
        $oferta->setHorafinala($horario[0]->getHoraafim());
        //
        $oferta->setHorainicialb(null);
        $oferta->setHorafinalb(null);
        $oferta->setIntervaloinicio(null);
        $oferta->setIntervalofinal(null);
    }
    else
    if ($parametros['horario'] == 3){
        $oferta->setHorainiciala($horario[0]->getHorabinicio());
        $oferta->setHorafinala($horario[0]->getHorabfim());
        //
        $oferta->setHorainicialb(null);
        $oferta->setHorafinalb(null);
        $oferta->setIntervaloinicio(null);
        $oferta->setIntervalofinal(null);
    }

    //preenchimento normal da OFERTA
    $oferta->setTurno($entityManager->find('Registro', $parametros['turno']));
    $oferta->setDiasemana($entityManager->find('Registro', $parametros['diasemana']));

    $entityManager->persist($oferta);

    //seta os parametros corretamente
    $model->setOferta($oferta);
    $model->setSala($entityManager->find('Sala', $parametros['sala']));
    $model->setSemestre($entityManager->find('Semestreletivo', $parametros['idsemestreletivo']));

    $entityManager->persist($model);
    $entityManager->flush();

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>

