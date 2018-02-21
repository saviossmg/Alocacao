<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 21/02/2018
 * Arquivo responsavel pela listagem de uma alocação para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
include 'getHash.php';

 
$data = [];
$data2 = [];

try{
    if(empty($_POST)){
        $mensagem = "HASH não informado";
        throw new Exception($mensagem);
    }
    else
    if(sha1($_POST["hash"]) != $hash) {
        $mensagem = "HASH incorreto!";
        throw new Exception($mensagem);
    }
    else{
		//se o id da alocacao vier vazia ele joga uma exceção
		if(empty($_POST['idalocacao'])){
            $mensagem = "Alocação não informada!";
			throw new Exception($mensagem);
        }
		
		$alocacao = $entityManager->find('Alocacaosala',$_POST['idalocacao']);	

        if (!empty($alocacao)) {        
			$data["id"] = $alocacao->getId();		
			$data["idsemestre"] = $alocacao->getSemestre()->getId();
			$data["idsala"] = $alocacao->getSala()->getId();
			$data["idoferta"] = $alocacao->getOferta()->getId();

			//procura a oferta
			$ofer = $entityManager->find('Oferta',$alocacao->getOferta()->getId());
			$data2["id"] = $ofer->getId();
			$data2["nometurma"] = $ofer->getNometurma();
			$data2["idcurso"] = $ofer->getCurso()->getId();
			$data2["diasemana"] = $ofer->getDiasemana()->getDescricao();
			$data2["periodo"] = $ofer->getPeriodo();
			$data2["disciplina"] = $ofer->getDisciplina();
			$data2["descricaoperiodoletivo"] = $ofer->getDescricaoperiodoletivo();
			$data2["horainiciala"] = $ofer->getHorainiciala();
			$data2["horafinala"] = $ofer->getHorafinala();
			$data2["intervaloinicio"] = $ofer->getIntervaloinicio();
			$data2["intervalofim"] = $ofer->getIntervalofinal();
			$data2["horainicialb"] = $ofer->getHorainicialb();
			$data2["horafinalb"] = $ofer->getHorafinalb();
			$data2["professor"] = $ofer->getProfessortitular();
			$data2["tipohorario"] = $ofer->getTipohorario();
			$data2["turno"] = null;
			if(!empty($ofer->getTurno()))
				$data2["turno"] = $ofer->getTurno()->getDescricao();
            
            $mensagem =  " 2 registros encontrados";
            $resultado = ['status' => true, 'mensagem' => $mensagem, 'aloc' => $data, 'ofer' => $data2 ];
        } else {
            // records now found
            $mensagem = "Nenhum registro foi encontrado.";
            $resultado = ['status' => false, 'mensagem' => $mensagem, 'aloc' => null, 'ofer' => null];
        }
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: ".$ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'aloc' => null, 'ofer' => null];
}
$retorno = json_encode($resultado);

echo $retorno;
 
?>