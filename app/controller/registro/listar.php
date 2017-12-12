<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 03/05/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//captura pelo post o id da entidade
$identidade =  $_POST['identidade'];
$tipo = "tipos".$_POST['tipo'];


// Design initial table header
$data = '<table id="'.$tipo.'" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Descrição</th>  
                    <th>Ativo</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("r")
    ->from('Registro', "r")
    ->where('r.identidade = :identidade')
    ->andWhere('r.idpai IS NULL')
    ->andWhere("r.id IS NOT NULL ")
    ->setParameter('identidade', $identidade);
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(r.id)");
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
                <td>' . $model->getDescricao() . '</td>
                <td>' . $ativo . '</td>
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

?>