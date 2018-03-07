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
$mensagem = "";

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
    if (empty($parametros['diasemana']) || empty($parametros['turno']) || empty($parametros['sala']) || empty($parametros['horario'])
        || empty($parametros['horario']))
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
        if (empty($parametros['idsemestreletivo'])) {
            $mensagem .= "5 - Semestre Letivo  <br>";
        }
        throw new Exception($mensagem);
    }
    //só faz a verificação na hora de inserir
    if(empty($parametros['id'])){
        //objetos necessários para as comparações
        $sala = $entityManager->find('Sala', $parametros['sala']);
        $ofer = $entityManager->find('Oferta', $parametros['oferta']);
        $semelet = $entityManager->find('Semestreletivo', $parametros['idsemestreletivo']);
        $alocacoes = getAlocacao($sala,$entityManager,$semelet);
        //verifica primeiro os tipos de sala antes de fazer a verificação geral
        if($sala->getTipo()->getId() == 1){
            //verifica se não tem nenhum registro
            if(!empty($alocacoes)){
                $match = false;
                //vai percorrer para verificar o turno
                //regra: um curso e um periodo devem ocupar um turno de uma sala apenas
                foreach ($alocacoes as $key => $val){
                    $periodo = $val->getOferta()->getPeriodo();
                    $curso = $val->getOferta()->getCurso()->getId();
                    $turno = $val->getOferta()->getTurno()->getId();
                    //vai verificar se o mesmo turno
                    if($turno == $parametros["turno"] && $curso == $ofer->getCurso()->getId()){
                        //verifica se são diferentes o curso e o periodo
                        if($periodo != $ofer->getPeriodo() && $periodo != 0)
                            $match = true;
                    }
                    //se der verdadeiro para alguma verificacao ele solta a exceção
                    if($match){
                        $mensagem .= '<br>Essa sala, no turno selecionado ('.$val->getOferta()->getTurno()->getDescricao().') esta disponivel ';
                        $mensagem .= 'apenas para o curso de '.$val->getOferta()->getCurso()->getNome();
                        if($periodo == 0)
                            $mensagem .=  ' para Regularização.';
                        else
                            $mensagem .=  ' no '.$periodo.'º Périodo';

                        throw new Exception($mensagem);
                    }

                }
            }

        }
        if($sala->getTipo()->getId() == 2){
            $usolabs = getLaboratorio($sala,$entityManager,$semelet);
            //vai verificar apenas as exclusividades do laboratorio selecionado
            if(!empty($usolabs)){
                $match = false;
                //faz uma repetição pois um mesmo laboratorio pode ter varias exceções
                foreach ($usolabs as $key => $lab){
                    //verifica por tipo de uso, cada tipo de uso tem uma restrição diferente
                    //monitoria
                    if($lab->getTipouso()->getId() == 19 ){
                        //barra se for no mesmo dia e turno
                        if($parametros["diasemana"] == $lab->getDia()->getId() && $parametros["turno"] == $lab->getTurno()->getId()){
                            $mensagem .= '<br>- Existe uma monitoria no laborátorio '.$lab->getLaboratorio()->getNome().' no Dia e Turno selecionados;';
                            $match = true;
                        }
                    }
                    //uso comum
                    if($lab->getTipouso()->getId() == 20 ){
                        //barra se for no mesmo turno
                        if($parametros["turno"] == $lab->getTurno()->getId()){
                            $mensagem .= '<br>- Existe um uso comum agendado no '.$lab->getLaboratorio()->getNome().' no Turno selecionados;';
                            $match = true;
                        }
                    }
                    //uso exclusivo
                    if($lab->getTipouso()->getId() == 21 ){
                        if($ofer->getCurso()->getId() != $lab->getCurso()->getId()){
                            $mensagem .= '<br>- Apenas o curso de '.$lab->getCurso()->getNome().' pode usar o '.$lab->getLaboratorio()->getNome();
                            $match = true;
                        }
                    }
                }
                //dispara a exceção
                if($match)
                    throw new Exception($mensagem);

            }

        }
        //se passou, faz a verificaççao geral
        //1 se tem alguma alocacao na mesma sala
        if(!empty($alocacoes) && $sala->getTipo()->getId() != 22 ){
            //verifica se tem algo no mesmo dia, turno e horario
            foreach ($alocacoes as $key => $val){
                if($parametros["diasemana"] == $val->getOferta()->getDiasemana()->getId() && $parametros["turno"] == $val->getOferta()->getTurno()->getId()
                    && $val->getOferta()->getPeriodo() != 0){
                    $match = false;
                    //verifica o horario, se for cheio ou igual,ele nao deixa passare
                    if($val->getOferta()->getTipohorario() == 3 ){
                        $mensagem .= '<br>Já existe uma OFERTA alocada neste Semestre para essa Sala, Turno e Horário (Horário já Completo)';
                        $match = true;
                    }
                    else if ($parametros["horario"] == $val->getOferta()->getTipohorario()){
                        $mensagem .= '<br>Já existe uma OFERTA alocada neste Semestre para essa Sala, Turno e Horário (Mesmo Horário)';
                        $match = true;
                    }
                    if($match)
                        throw new Exception($mensagem);
                }
            }
        }

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
        $oferta->setTipohorario(3);
    }
    else
    if ($parametros['horario'] == 2){
        $oferta->setHorainiciala($horario[0]->getHoraainicio());
        $oferta->setHorafinala($horario[0]->getHoraafim());
        $oferta->setTipohorario(1);
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

        $oferta->setTipohorario(2);
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

/*
 * Metodo que pega uma oferta da sala
 * @param sala, entityManager, semestreletivo
 * @return objeto alocacao
 */
function getAlocacao($sala, $eM, $semelet){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select('Al,Sel,Sa,O,Se')
        ->from('Alocacaosala', 'Al')
        ->innerJoin('Al.oferta', 'O')
        ->innerJoin('Al.semestre', 'Sel')
        ->innerJoin('Sel.semestre', 'Se')
        ->innerJoin('Al.sala', 'Sa')
        ->where('Sa.id = :sala')
        ->andWhere('Se.id = :semestre')
        ->setParameter('sala', $sala->getId())
        ->setParameter('semestre', $semelet->getSemestre()->getId());

    return $qb->getQuery()->getResult();
}

/*
 * Metodo que pega uma exceção de laboratorio
 * @param lab, entityManager, semestreletivo
 * @return objeto laboratiro
 */
function getLaboratorio($lab, $eM, $semelet){
    //seleciona as salas
    $qb = $eM->createQueryBuilder();
    $qb->select("La,Se,S,T,Tu,D,Cu")
        ->from('Laboratorio', "La")
        ->innerJoin("La.semestre", "Se")
        ->innerJoin("La.laboratorio", "S")
        ->innerJoin("La.tipouso", "T")
        ->leftJoin("La.turno", "Tu")
        ->leftJoin("La.dia", "D")
        ->leftJoin("La.curso", "Cu")
        ->where('S.id = :lab')
        ->andWhere('Se.id = :semestre')
        ->setParameter('lab', $lab->getId())
        ->setParameter('semestre', $semelet->getSemestre()->getId());

    return $qb->getQuery()->getResult();
}

?>

