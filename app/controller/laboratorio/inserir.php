<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 18/11/2017
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
        'laboratorio' => $_POST['lab'],
        'tipouso' => $_POST['tipouso'],
        'turno' => $_POST['turnolab'],
        'dia' => $_POST['dia'],
        'observacao' => $_POST['observacao'],
        'semestre' => $_POST['semestre'],
        'curso' => $_POST['curso']
    ];

    //validação dos campos
    if (empty($parametros['semestre']) || empty($parametros['observacao']) || empty($parametros['laboratorio']) ||
        empty($parametros['tipouso'])
    ) {
        $mensagem = "Os seguintes campos não podem vir vazios: <br>";
        if (empty($parametros['semestre']))
            $mensagem .= "1 - Semestre  <br>";
        if (empty($parametros['observacao']))
            $mensagem .= "2 - Observação  <br>";
        if (empty($parametros['laboratorio']))
            $mensagem .= "3 - Laborátorio  <br>";
        if (empty($parametros['tipouso']))
            $mensagem .= "4 - Tipo de Uso  <br>";
        throw new Exception($mensagem);
    }

    //só vai verificar se tiver inserindo pela primeira vez
    if(empty($parametros['id'])){
        //dados para verificacao
        $aloc = getAlocacoes($parametros['laboratorio'],$parametros['semestre'],$entityManager);
        $labs = getLaboratorios($parametros['laboratorio'],$parametros['semestre'],$entityManager);
        //verifica, pelo tipo de uso, o que é requerido para travar
        if($parametros["tipouso"] == 19 /*monitoria*/){
            //barra os parametros vazios
            if( empty($parametros['dia']) || empty($parametros['curso']) || empty($parametros['turno'])){
                $mensagem = "Os seguintes campos não podem vir vazios: <br>";
                if (empty($parametros['dia']))
                    $mensagem .= "5 - Dia  <br>";
                if (empty($parametros['curso']))
                    $mensagem .= "6 - Curso  <br>";
                if (empty($parametros['turno']))
                    $mensagem .= "7 - Turno  <br>";
                throw new Exception($mensagem);
            }
            else{
                //verifica as alocações
                if(!empty($aloc)){
                    //verifica e barra se for no mesmo dia e turno
                    foreach ($aloc as  $key => $val) {
                        if($val->getOferta()->getDiasemana()->getId() == $parametros['dia'] && $val->getOferta()->getTurno()->getId() == $parametros['turno']){
                            //se entrar joga um exception
                            $mensagem = "Já existe uma Oferta alocada para esse laborátorio no Dia, Turno e Semestre selecioandos.<br>";
                            throw new Exception($mensagem);
                        }
                    }
                }
                //verifica os laboratorios
                if(!empty($labs)){
                    foreach ($labs as  $key => $val) {
                        //vai verificar os tipo
                        if($val->getTipouso()->getId() == 19){
                            if($val->getTurno()->getId() == $parametros['turno'] && $val->getDia()->getId() == $parametros['dia']){
                                //se entrar joga um exception
                                $mensagem = "Já existe uma Monitoria para esse laborátorio no Dia, Turno e Semestre selecioandos.<br>";
                                throw new Exception($mensagem);
                            }
                        }
                        if($val->getTipouso()->getId() == 20){
                            if($val->getTurno()->getId() == $parametros['turno']){
                                //se entrar joga um exception
                                $mensagem = "Esse laborátorio é de Uso Comun no Turno e Semestre selecioandos.<br>";
                                throw new Exception($mensagem);
                            }
                        }
                        if($val->getTipouso()->getId() == 21){
                            if($val->getCurso()->getId() != $parametros['curso']){
                                //se entrar joga um exception
                                $mensagem = "Esse laborátorio é de Uso Exclusivo e só pode aceitar monitorias e alocações para o curso de ";
                                $mensagem .= $val->getCurso()->getNome()." no Semestre selecioando.<br>";
                                throw new Exception($mensagem);
                            }
                        }
                    }
                }
            }
        }
        if($parametros["tipouso"] == 20 /*uso comum*/){
            //barra os parametros vazios
            if( empty($parametros['turno'])){
                $mensagem = "Os seguintes campos não podem vir vazios: <br>";
                $mensagem .= "7 - Turno  <br>";
                throw new Exception($mensagem);
            }
            else{
                //verifica as alocações
                if(!empty($aloc)){
                    //verifica e barra se for no mesmo turno
                    foreach ($aloc as  $key => $val) {
                        if($val->getOferta()->getTurno()->getId() == $parametros['turno']){
                            //se entrar joga um exception
                            $mensagem = "Já existe uma Oferta alocada para esse laborátorio no Turno e Semestre selecioandos.<br>";
                            throw new Exception($mensagem);
                        }
                    }
                }
                //verifica os laboratorios
                if(!empty($labs)){
                    foreach ($labs as  $key => $val) {
                        //vai verificar os tipo
                        if($val->getTipouso()->getId() == 19){
                            if($val->getTurno()->getId() == $parametros['turno']){
                                //se entrar joga um exception
                                $mensagem = "Existe uma Monitoria para esse laborátorio no Turno e Semestre selecioandos.<br>";
                                throw new Exception($mensagem);
                            }
                        }
                        if($val->getTipouso()->getId() == 20){
                            if($val->getTurno()->getId() == $parametros['turno']){
                                //se entrar joga um exception
                                $mensagem = "Esse laborátorio já de Uso Comun no Turno e Semestre selecioandos.<br>";
                                throw new Exception($mensagem);
                            }
                        }
                        if($val->getTipouso()->getId() == 21){
                            //se entrar joga um exception
                            $mensagem = "Esse laborátorio é de Uso Exclusivo e só pode aceitar monitorias e alocações para o curso de ";
                            $mensagem .= $val->getCurso()->getNome()." no Semestre selecioando.<br>";
                            throw new Exception($mensagem);
                        }
                    }
                }
            }
        }
        if($parametros["tipouso"] == 21 /*exclusivo*/){
            //barra os parametros vazios
            if(empty($parametros['curso'])){
                $mensagem = "Os seguintes campos não podem vir vazios: <br>";
                $mensagem .= "6 - Curso  <br>";
                throw new Exception($mensagem);
            }
            else{
                //verifica as alocações
                if(!empty($aloc)){
                    //verifica e barra se for cursos diferentes
                    foreach ($aloc as  $key => $val) {
                        if($val->getOferta()->getCurso()->getId() != $parametros['curso']){
                            //se entrar joga um exception
                            $mensagem = "Já existe uma Oferta alocada para esse laborátorio de um Curso diferente do selecionado.<br>";
                            throw new Exception($mensagem);
                        }
                    }
                }
                //verifica os laboratorios
                if(!empty($labs)){
                    foreach ($labs as  $key => $val) {
                        //vai verificar os tipo
                        if($val->getTipouso()->getId() == 19){
                            if($val->getCurso()->getId() != $parametros['curso']){
                                //se entrar joga um exception
                                $mensagem = "Existe uma Monitoria para esse laborátorio de Curso diferente do selecionado.<br>";
                                throw new Exception($mensagem);
                            }
                        }
                        if($val->getTipouso()->getId() == 20){
                                //se entrar joga um exception
                                $mensagem = "Esse laborátorio já de Uso Comun no Turno e Semestre selecioandos e não pode se tornar de Uso Exclusivo.<br>";
                                throw new Exception($mensagem);
                        }
                        if($val->getTipouso()->getId() == 21){
                            //se entrar joga um exception
                            $mensagem = "Esse laborátorio já é de Uso Exclusivo para o curso de ";
                            $mensagem .= $val->getCurso()->getNome()." no Semestre selecioando.<br>";
                            throw new Exception($mensagem);
                        }
                    }
                }
            }
        }
    }
    //salvar
    $model;
    if (empty($parametros['id'])) {
        //instancia novo model
        $model = new Laboratorio;
        $mensagem = "Registro inserido com SUCESSO!";
    } //atualizar
    else {
        $model = $entityManager->find('Laboratorio', $parametros['id']);
        $mensagem = "Registro atualizado com SUCESSO!";

    }
    $semestre = $entityManager->find('Vwsemestre', $parametros['semestre']);
    $laboratorio = $entityManager->find('Sala', $parametros['laboratorio']);
    $tipouso = $entityManager->find('Registro', $parametros['tipouso']);
    $dia = $entityManager->find('Registro', $parametros['dia']);
    $turno = $entityManager->find('Registro', $parametros['turno']);
    $curso = $entityManager->find('Vwcurso', $parametros['curso']);

    //seta os parametros corretamente
    $model->setObservacao($parametros['observacao']);
    //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
    $model->setSemestre($semestre);
    $model->setLaboratorio($laboratorio);
    $model->setTipouso($tipouso);

    //insere conforme o tipo de uso
    $model->setTurno(null);
    $model->setDia(null);
    $model->setCurso(null);
    if($parametros['tipouso'] == 19){
        $model->setDia($dia);
        $model->setCurso($curso);
        $model->setTurno($turno);
    }
    if($parametros['tipouso'] == 20){
        $model->setTurno($turno);
    }
    if($parametros['tipouso'] == 21){
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


/*
 * Metodo que irá pegar as alocaçoes do laboratorio para verificar
 * @param idLab, semestreletivo
 * @return objeto alocacao
 */
function getAlocacoes($lab,$sem,$eM){
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
        ->setParameter('sala', $lab)
        ->setParameter('semestre', $sem);

    return $qb->getQuery()->getResult();

}

/*
 * Metodo que irá pegar as alocaçoes do laboratorio para verificar
 * @param lab, semestreletivo
 * @return objeto laboratiro
 */
function getLaboratorios($lab,$sem,$eM){
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
        ->setParameter('lab', $lab)
        ->setParameter('semestre', $sem);

    return $qb->getQuery()->getResult();

}

?>