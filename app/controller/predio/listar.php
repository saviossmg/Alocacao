<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 30/05/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'].'/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = '<table id="predios" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Nome</th>  
                    <th>Unidade</th>  
                    <th>Ativo</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("p, u")
    ->from('Predio', "p")
    ->leftJoin("p.unidade", "u")
    ->andWhere("p.id IS NOT NULL ");
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(p.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        $unidade = $model->getUnidade();
        $ativo = "";

        if (empty($unidade)) {
            $unidade = "<span class=\"label label-danger\">Nenhuma</span>";
        } else {
            $unidade = "<span class=\"label label-info\"> " . $model->getUnidade()->getNome() . "</span>";
        }

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
                <td>' . $unidade . '</td>
                <td>' . $ativo . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement= "top" title="Detalhes" class="btn btn-success"  onclick="detalhes('.$model->getId().')">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"/>
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