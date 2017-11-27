<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 18/11/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Alocacao\\');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = '<table id="laboratorios" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Nome</th>  
                    <th>Tipo de Uso</th>  
                    <th>Observação</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

//cria a query builder
$semestre = $entityManager->find('Vwsemestre', $_POST['idSemestre']);

//cria a query builder
$qb = $entityManager->createQueryBuilder();
$qb->select("c, s, sa, tU, t, d")
    ->from('Laboratorio', "c")
    ->innerJoin("c.semestre", "s")
    ->innerJoin("c.laboratorio", "sa")
    ->innerJoin("c.tipouso", "tU")
    ->leftJoin("c.turno", "t")
    ->leftJoin("c.dia", "d")
    ->where("s.id = :semestreP")
    ->andWhere("c.id IS NOT NULL ")
    ->setParameter("semestreP",$semestre->getId());
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(c.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    $numero = 1;
    foreach ($rs as $idx => $model) {
        $data .= '
            <tr>
                <td>' . $numero . '</td>
                <td>' . $model->getLaboratorio()->getNome() . '</td>
                <td>' . $model->getTipouso()->getDescricao() . '</td>
                <td>' . $model->getObservacao() . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement="top" title="Editar" onclick="editarLab(' . $model->getId() . ')" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Excluir" onclick="deletarLab(' . $model->getId() . ')" class="btn btn-danger">
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