<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 09/06/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = '<table id="disciplinas" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Descrição</th>  
                    <th>Cod. Curricular</th>  
                    <th>Período</th>  
                    <th>Curso</th>  
                    <th>Pré-Requisito</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("d, c, dpr")
    ->from('Vwdisciplina', "d")
    ->leftJoin("d.curso", "c")
    ->leftJoin("d.prerequesito", "dpr")
    ->andWhere("d.id IS NOT NULL ");
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(d.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        if(!empty($model->getCurso())){
            $curso = "<span class=\"label label-success\"> " .$model->getCurso()->getNome()." </span>";
        }
        else {
            $curso = "<span class=\"label label-danger\">Nenhum</span>";
        }

        if(!empty($model->getPrerequesito())){
            $pre = "<span class=\"label label-success\">Sim</span>";
        }
        else {
            $pre = "<span class=\"label label-danger\">Não</span>";
        }
        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getDescricao() . '</td>
                <td>' . $model->getCodcurricular() . '</td>
                <td>' . $model->getPeriodo() . '</td>
                <td>' . $curso . '</td>
                <td>' . $pre . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement="top" title="Editar" onclick="editar(' . $model->getId() . ')" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
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