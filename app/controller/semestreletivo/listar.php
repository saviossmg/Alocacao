<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 08/09/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = '<table id="semestresletivos" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Curso</th>  
                    <th>Semestre</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
if(empty($_POST['idSemestre']) || $_POST['idSemestre']==0){
    $qb->select("sl, s, c")
        ->from('Semestreletivo', "sl")
        ->innerJoin("sl.semestre", "s")
        ->innerJoin("sl.curso", "c")
        ->andWhere("sl.id IS NOT NULL ")
        ->orderBy("s.id",'DESC');
}
else{
    $qb->select("sl, s, c")
        ->from('Semestreletivo', "sl")
        ->innerJoin("sl.semestre", "s")
        ->innerJoin("sl.curso", "c")
        ->where("sl.semestre = :semestreId ")
        ->andWhere("sl.id IS NOT NULL ")
        ->setParameter("semestreId", $_POST['idSemestre']);

}


$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(sl.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {

        $semestre = "<span class=\"label label-info\"> " . $model->getSemestre()->getDescricao() . "</span>";
        $curso = "<span class=\"label label-success\">" . $model->getCurso()->getNome() . "</span>";

        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $curso . '</td>
                <td>' . $semestre . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement= "top" title="Detalhes" class="btn btn-success"  onclick="detalhes('.$model->getId().')">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"/>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Excluir" onclick="deletar(' . $model->getId() . ')" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>';
        $numero++;
    }
} else {
    // records now found
    $data .= '<tr><td colspan="6">Nenhum registro encontrado!</td></tr>';
}
$data .= ' </tbody>  
        </table>  
';

echo $data;