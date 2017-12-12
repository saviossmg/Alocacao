<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 05/11/2017
 * Arquivo responsavel pela listagem de todos os itens e construção da tabela de exibição
 * Diferente das outras listagem, a mesma é estatica
 */

// Design initial table header
$data = '<table id="relatorios" class="table table-condensed-bordered">
            <thead>  
                <tr>  
                    <th>No.</th>  
                    <th>Tipo</th>  
                    <th>Descrição</th>  
                    <th class="actions">Ações</th> 
                </tr>  
            </thead>
            <tbody>';

$numero = 3;
for($i = 1; $i <= $numero; $i++ ) {
    $tipo = "";
    $descrição = "";
    if($i == 1){
        $tipo = "Identificação de Salas";
        $descrição = "Relatório que identifica a sala com curso, periodo e dias que está ocupada ou livre. Fica sempre na porta da sala.";
    }
    if($i == 2){
        $tipo = "Mapa de Uso do Curso";
        $descrição = "Relatório que identifica as salas utilizadas por um curso. Fica nos murais dos blocos.";
    }
    if($i == 3){
        $tipo = "W.I.P";
        $descrição = "W.I.P";
    }
    $data .= '
            <tr>
                <td>' . $i . '</td>
                <td>' . $tipo. '</td>
                <td>' . $descrição . '</td>
                <td class="actions">	
                    <button data-toggle="tooltip" data-placement= "top" title="Imprimir" class="btn btn-info"  onclick="imprimir('.$i.')">
                        <span class="glyphicon glyphicon-print" aria-hidden="true"/>
                    </button>                    
                </td>
            </tr>';
}
$data .= ' </tbody>  
        </table>  
';

echo $data;