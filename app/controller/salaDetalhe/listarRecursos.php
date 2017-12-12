<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 19/06/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
$sala = $_POST['idSala'];

// Design initial table header
$data = '<table id="recursosSala" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Descrição</th>  
                    <th>Tipo</th>  
                    <th>Quantidade</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("e, s, r")
    ->from('Recursossalas', "e")
    ->innerJoin("e.sala", "s")
    ->innerJoin("e.registro", "r")
    ->where('s.id = :sala')
    ->andWhere("e.id IS NOT NULL ")
    ->setParameter('sala', $sala);
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(e.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getRegistro()->getDescricao() . '</td>
                <td>' . $model->getRegistro()->getIdpai()->getDescricao() . '</td>
                <td>' . $model->getQuantidade() . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement= "top" title="Detalhes" class="btn btn-warning"  onclick="editarRecurso('.$model->getId().')">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"/>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Desvincular" onclick="deletarRecurso(' . $model->getId() . ')" class="btn btn-danger">
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