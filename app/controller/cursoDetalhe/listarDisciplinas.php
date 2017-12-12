<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 19/06/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
$curso = $_POST['idCurso'];

// Design initial table header
$data = '<table id="cursoDisciplinas" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Nome</th>  
                    <th>Código</th>  
                    <th>Pré-Requisito</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("d, c, p")
    ->from('Vwdisciplina', "d")
    ->innerJoin("d.curso", "c")
    ->leftJoin("d.prerequesito", "p")
    ->where('c.id = :curso')
    ->andWhere("d.id IS NOT NULL ")
    ->setParameter('curso', $curso);
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(d.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        $prerequesito = null;
        if(empty($model->getPrerequesito())){
            $prerequesito = "<span class=\"label label-danger\">Nenhuma</span>";
        }
        else{
            $prerequesito = "<span class=\"label label-info\"> " . $model->getPrerequesito()->getDescricao() . "</span>";
        }
        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getDescricao() . '</td>
                <td>' . $model->getCodcurricular(). '</td>
                <td>' . $prerequesito. '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement="top" title="Desvincular" onclick="desvincularDisciplina(' . $model->getId() . ')" class="btn btn-danger">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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