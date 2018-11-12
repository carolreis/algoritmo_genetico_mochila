<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("functions/functions.php");
require_once("classes/item.class.php");
require_once("classes/individuo.class.php");
require_once("classes/ag.class.php");

$lista_itens = array();

array_push($lista_itens, new Item("Celular", 200, 2500.00));
array_push($lista_itens, new Item("Carteira", 500, 200.00));
array_push($lista_itens, new Item("Escova de cabelo", 100, 20.00));
array_push($lista_itens, new Item("Relógio", 200, 1000.00));
array_push($lista_itens, new Item("Notebook", 2000, 4000.00));
array_push($lista_itens, new Item("Notebook 2", 2100, 2234.55));

$espacos = array();
$valores = array();
$nomes = array();

foreach ($lista_itens as $item) {
	array_push($espacos, $item->espaco);
	array_push($valores, $item->valor);
	array_push($nomes, $item->nome);
}

/* Get POST data */
$data = json_decode(file_get_contents('php://input'));

$limite = isset($data->limite_espaco) ? $data->limite_espaco : 7000;
$tamanho_populacao = isset($data->tamanho_populacao) ? $data->tamanho_populacao : 20;
$taxa_mutacao = isset($data->taxa_mutacao) ? $data->taxa_mutacao : 0.01;
$numero_geracoes = isset($data->quantidade_geracoes) ? $data->quantidade_geracoes : 100;

/* Debugging purpose */
echo "\nLimite: ".$limite;
echo "\nTamanho população: ".$tamanho_populacao;
echo "\ntaxa_mutacao: ".$taxa_mutacao;
echo "\nQuantidade Gerações: ".$numero_geracoes;

$ag = new AlgoritmoGenetico($tamanho_populacao);

$resultado = $ag->resolver($taxa_mutacao, $numero_geracoes, $espacos, $valores, $limite);

http_response_code(200);
echo "\nCromossomo: ";
echo json_encode($resultado);