<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 07/09/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = '<table id="semestres" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Descrição</th>  
                    <th>Inicio</th>  
                    <th>Fim</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("s")
    ->from('Vwsemestre', "s")
    ->andWhere("s.id IS NOT NULL ");
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(s.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        //as datas são formatadas de objeto datetime para string
        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getDescricao() . '</td>                
                <td>' . date_format($model->getDatainicio(), 'd/m/Y') . '</td>
                <td>' . date_format($model->getDatafim(), 'd/m/Y') . '</td>
                <td class="actions">
                	<button data-toggle="tooltip" data-placement= "top" title="Alocação Semestral" class="btn btn-success"  onclick="detalhes('.$model->getId().')">
                        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"/>
                    </button>
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