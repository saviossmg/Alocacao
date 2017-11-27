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
        'semestre' => $_POST['semestre'],
        'curso' => $_POST['curso']
    ];

    //validação dos campos
    if (empty($parametros['semestre']) || empty($parametros['curso'])) {
        $mensagem = "Os seguintes campos não podem vir vazios:<br/>";
        if (empty($parametros['semestre'])) {
            $mensagem .= "1 - Semestre  <br/>";
        }
        if (sizeof($parametros['curso']) <= 0) {
            $mensagem .= "2 - Curso  <br/>";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model;
    if (empty($parametros['id'])) {
        $semestre = $entityManager->find('Vwsemestre', $parametros['semestre']);
        //instancia novo model num loop
        $mensagem = "Registro inserido com SUCESSO!<br/>";
        foreach($parametros['curso'] as &$val){
            $curso = $entityManager->find('Vwcurso', $val);
            //verifica se tem algum registro já inserido com semestre e curso requisitados
            $qb = $entityManager->createQueryBuilder();
            $qb->select("sl, S, C")
                ->from('Semestreletivo', "sl")
                ->leftJoin("sl.semestre", "S")
                ->leftJoin("sl.curso", "C")
                ->where("S.id = :semestre  ")
                ->andWhere("C.id = :curso  ")
                ->setParameter('semestre', $parametros['semestre'])
                ->setParameter('curso', $val);
            $rs = $qb->getQuery()->getResult();
            $qCount = clone $qb;
            $qCount->select("count(sl.id)");
            $totalregistro = $qCount->getQuery()->getSingleScalarResult();

            // se ano retornar nada ele insere
            if($totalregistro == 0){
                $model = new Semestreletivo();
                $curso = $entityManager->find('Vwcurso', $val);
                $model->setCurso($curso);
                $model->setSemestre($semestre);

                $entityManager->persist($model);
                $entityManager->flush();

                $mensagem .= "Curso ".$curso->getNome()." INSERIDO. <br/>";

            }else{
                $mensagem .= "Curso ".$curso->getNome()." já está inserido nesse semestre. <br/>";
            }

        }

    } //atualizar
    else {
        //procura

    }

    $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];

} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);

?>