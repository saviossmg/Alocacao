<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 21/09/2017
 * Arquivo responsavel pela listagem de todos os cursos para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
include 'getHash.php';

$mensagem;
$data = [];
$resultado = [];

try {
    if(empty($_GET)){
        $mensagem = "OFFSET não informado";
        throw new Exception($mensagem);
    }
    else
        if($_GET["offset"] < 0 || $_GET["offset"] == ""){
            $mensagem = "OFFSET invalido!";
            throw new Exception($mensagem);
        }
        else{
            //URL's do serviço de oferta
            $url = 'https://www.unitins.br/webapi/api/noticia/?offset='.$_GET["offset"];

            //$url = str_replace(" ", "%20", $url);

            //criação do header
            $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

            //download do conteudo do webservice
            $xml = file_get_contents($url, false, $context);

            $news = json_decode(json_encode(simplexml_load_string($xml)), 1);

            foreach($news["Noticia"] as $key => $value){
                $data[$key]["id"] = $value["id"];
                $data[$key]["autor"] = $value["autor"];
                $data[$key]["titulo"] = $value["titulo"];
                $data[$key]["subTitulo"] = $value["subTitulo"];
                $data[$key]["palavrasChave"] = $value["palavrasChave"];
                $data[$key]["dataCriacao"] = $value["dataCriacao"];
                $data[$key]["dataAtualizacao"] = $value["dataAtualizacao"];
                $data[$key]["dataPublicacao"] = $value["dataPublicacao"];
                $data[$key]["chapeu"] = $value["chapeu"];
                $data[$key]["texto"] = $value["texto"];
            }
            $mensagem = "Noticias carregadas com sucesso!";
            $resultado = $data;
        }
} catch (Exception $ex) {
    $mensagem = "Atenção: ".$ex->getMessage();
    $resultado = [];
}
$retorno = json_encode($resultado);

echo $retorno;
?>