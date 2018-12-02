<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("functions/functions.php");
require_once("classes/item.class.php");
require_once("classes/individuo.class.php");
require_once("classes/ag.class.php");

$lista_itens = array();

// Nome, espaço, valor
array_push($lista_itens, new Item("Celular", 200, 2500.00));
array_push($lista_itens, new Item("Carteira", 500, 200.00));
array_push($lista_itens, new Item("Escova de cabelo", 100, 20.00));
array_push($lista_itens, new Item("Relógio", 200, 1000.00));
array_push($lista_itens, new Item("Notebook", 2000, 4000.00));
array_push($lista_itens, new Item("Notebook 2", 2100, 2234.55));
array_push($lista_itens, new Item("item 7", 1400, 1434.55));
array_push($lista_itens, new Item("item 8", 100, 145.55));
array_push($lista_itens, new Item("item 9", 776, 335.64));
array_push($lista_itens, new Item("item 10", 10, 12.00));

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

$tamanho_populacao = isset($data->tamanho_populacao) ? $data->tamanho_populacao : 10;
$numero_geracoes = isset($data->quantidade_geracoes) ? $data->quantidade_geracoes : 100;
$numero_pais = isset($data->quantidade_pais) ? $data->quantidade_pais: 2;
$numero_individuos = isset($data->numero_individuos) ? $data->numero_individuos: 2; // novos individuos
$taxa_mutacao = isset($data->taxa_mutacao) ? $data->taxa_mutacao : 0.01;
$bits_mutacao = isset($data->bits_mutacao) ? $data->bits_mutacao: 5;
$peso_maximo = isset($data->peso_maximo) ? $data->peso_maximo: 7000;
$peso_maximo_obj = isset($data->peso_maximo_obj) ? $data->peso_maximo_obj: 7000;
$peso_min_obj = isset($data->peso_min_obj) ? $data->peso_min_obj: 50;
$valor_maximo_obj = isset($data->valor_maximo_obj) ? $data->valor_maximo_obj: 7000;
$valor_min_obj = isset($data->valor_min_obj) ? $data->valor_min_obj: 50;

/*
* For debugging
echo "\nTamanho População: ".$tamanho_populacao;
echo "\nNúmero Gerações: ".$numero_geracoes;
echo "\nNúmero Pais: ".$numero_pais;
echo "\nNúmero de Indivíduos: ".$numero_individuos;
echo "\nTaxa_de Mutacao: ".$taxa_mutacao;
echo "\nBits Mutacao: ".$bits_mutacao;
echo "\nPeso Máximo: ".$peso_maximo;
echo "\nPeso Máximo Objeto: ".$peso_maximo_obj;
echo "\nPeso Mínimo Objeto: ".$peso_min_obj;
echo "\nValor Máximo Objeto: ".$valor_maximo_obj;
echo "\nValor Mínimo Objeto: ".$valor_min_obj;
*/

$params = array(
	'tamanho_populacao' => $tamanho_populacao,
	'numero_geracoes' => $numero_geracoes,
	'numero_pais' => $numero_pais,
	'numero_individuos' => $numero_individuos,
	'taxa_mutacao' => $taxa_mutacao,
	'bits_mutacao' => $bits_mutacao,

	'peso_maximo' => $peso_maximo,
	
	'peso_maximo_obj' => $peso_maximo_obj,
	'peso_min_obj' => $peso_min_obj,

	'valor_maximo_obj' => $valor_maximo_obj,
	'valor_min_obj' => $valor_min_obj,

	'espacos' => $espacos, // array 
	'valores'=> $valores, // array 
	'nomes' => $nomes // array
);

$ag = new AlgoritmoGenetico($tamanho_populacao);
$resultado = $ag->resolver($params);

// echo "\n Cromossomo: \n";
http_response_code(200);
// echo json_encode($resultado);

$r = array();

for ($i=0; $i < count($espacos); $i++) {
	if ($resultado[$i] == 1) {
		$arr = array(
			'nome' => $nomes[$i],
			'valor' => $valores[$i],
			'peso' => $espacos[$i]
		);
		array_push($r, $arr);
	}
}

echo (json_encode($r));