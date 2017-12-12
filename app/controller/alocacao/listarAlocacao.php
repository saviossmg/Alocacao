<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 17/09/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = '<table id="alocacoes" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Disciplina</th>   
                    <th>Sala</th>  
                    <th>Dia</th>  
                    <th>Turno</th>                      
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
$qb->select("c, s, o, sA")
    ->from('Alocacaosala', "c")
    ->leftJoin("c.semestre", "s")
    ->leftJoin("c.oferta", "o")
    ->leftJoin("c.sala", "sA")
    ->where("s.semestre = :semestreP ")
    ->andWhere("s.curso = :cursoP")
    ->andWhere("c.id IS NOT NULL ")
    ->setParameter("semestreP",$semestre->getId())
    ->setParameter("cursoP",$curso->getId())
    ->orderBy("o.periodo",'ASC');
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(c.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

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
                <td>' . $model->getOferta()->getDisciplina() . '</td>
                <td>' . $model->getSala()->getNome() . '</td>
                <td>' . $model->getOferta()->getDiasemana()->getDescricao() . '</td>
                <td>' . $model->getOferta()->getTurno()->getDescricao() . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement="top" title="Editar" onclick="editarAlocacao(' . $model->getId() . ')" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </button>
                    <button data-toggle="tooltip" data-placement="top" title="Excluir" onclick="deletarAlocacao(' . $model->getId() . ')" class="btn btn-danger">
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