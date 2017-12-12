<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 09/06/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

$curso = $_POST['idCurso'];
$semestre = $_POST['idSemestre'];


// Design initial table header
$data = '<table id="ofertas" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Disciplina</th>  
                    <th>Periodo</th>  
                    <th>Professor Titular</h>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$curso = $entityManager->find('Vwcurso', $_POST['idCurso']);
$semestre = $entityManager->find('Vwsemestre', $_POST['idSemestre']);

//procura os registros no banco
//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("o, c, ds, t")
    ->from('Oferta', "o")
    ->leftJoin("o.curso", "c")
    ->leftJoin("o.diasemana", "ds")
    ->leftJoin("o.turno", "t")
    ->where("c.id = :cursoP")
    ->andWhere("o.codperiodoletivo LIKE :semestreP")
    ->andWhere("o.id IS NOT NULL ")
    ->setParameter("cursoP",$curso->getId())
    ->setParameter("semestreP",$semestre->getDescricao())
    ->orderBy("o.periodo",'ASC');
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(o.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        $periodo;
        $oferta = $entityManager->getRepository('Alocacaosala')->findBy(array('oferta' =>  $model->getId()));
        if(empty($oferta)){
            if($model->getPeriodo() == 0){
                $periodo = "Especial";
            }
            else{
                $periodo = $model->getPeriodo()." º";
            }
            $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getDisciplina() . '</td>
                <td>' . $periodo . '</td>
                <td>' . $model->getProfessortitular(). '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement= "top" title="Alocar" class="btn btn-success"  onclick="prepararAlocacaoOferta('.$model->getId().')">
                        <span class="glyphicon glyphicon-map-marker" aria-hidden="true"/>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Replicar Oferta" onclick="copiarOferta(' . $model->getId() . ')" class="btn btn-info">
                        <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Excluir" onclick="deletarOferta(' . $model->getId() . ')" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>';
            $numero++;
        }
    }
} else {
    // records now found
    $data .= '<tr><td colspan="6">Nenhum registro encontrado!</td></tr>';
}
$data .= ' </tbody>  
        </table>  
';
echo $data;
?>