<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 01/02/2018
 * Arquivo responsavel pela listagem do HASH
 */

require_once BASE_DIR . 'vendor/bootstrap.php';

//função aqui pega do banco
$model = $entityManager->find('Criptografia',1);

$hash = $model->getChave();