<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 19/06/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
$predio = $_POST['idPredio'];

// Design initial table header
$data = '<table id="salasPredio" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Nome</th>  
                    <th>Ativo</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("s, p")
    ->from('Sala', "s")
    ->innerJoin("s.predio", "p")
    ->where('p.id = :predio')
    ->andWhere("s.id IS NOT NULL ")
    ->setParameter('predio', $predio);
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(s.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        if($model->getAtivo()){
            $ativo = "<span class=\"label label-success\">Sim</span>";
        }
        else {
            $ativo = "<span class=\"label label-danger\">Não</span>";
        }
        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getNome() . '</td>
                <td>' . $ativo . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement= "top" title="Detalhes" class="btn btn-success"  onclick="detalhesSala('.$model->getId().')">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"/>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Desvincular" onclick="desvincularSala(' . $model->getId() . ')" class="btn btn-danger">
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