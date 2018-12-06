<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("functions/functions.php");
require_once("classes/item.class.php");
require_once("classes/individuo.class.php");
require_once("classes/ag.class.php");

/* Get POST data */
$data = json_decode(file_get_contents('php://input'));

$peso_maximo = isset($data->weight) ? $data->weight: 20; // 20 kg
$quantidade_objetos = isset($data->objectQuantity) ? $data->objectQuantity : 10; // quantidade de itens para tentar fazer caber
$valor_maximo_obj = isset($data->objectValues[0] ) ? $data->objectValues[0] : 4000; // valor máximo $
$valor_min_obj = isset($data->objectValues[1] ) ? $data->objectValues[1] : 30; // valor minimo $
$tamanho_populacao = isset($data->population) ? $data->population : 20; // quantidade individuos

$peso_min_obj = isset($data->objectWeight[0]) ? $data->objectWeight[0] : 0.1; // 0.5kg / 500g
$peso_maximo_obj = isset($data->objectWeight[1] ) ? $data->objectWeight[1] : 5; // 20kg
$taxa_mutacao = isset($data->mutationRate) ? ($data->mutationRate / 100) : 0.05; // como vem?

$numero_geracoes = isset($data->generationQuantity) ? $data->generationQuantity : 100; // quantidade de loop hehe
$bits_mutacao = isset($data->geneMutation) ? $data->geneMutation : round(($quantidade_objetos / 3));

// $numero_pais = isset($data->quantidade_pais) ? $data->quantidade_pais: 2;
// $numero_individuos = isset($data->numero_individuos) ? $data->numero_individuos: 2; // novos individuos

$lista_itens = array();

// array_push($lista_itens, new Item("Carteira", 0.5, 200.00));
// array_push($lista_itens, new Item("Escova de cabelo", 0.2, 20.00));
// array_push($lista_itens, new Item("Relógio", 0.8, 1000.00));
// array_push($lista_itens, new Item("Notebook", 2.0, 4000.00));
// array_push($lista_itens, new Item("Notebook 2", 1.3, 2234.55));
// array_push($lista_itens, new Item("item 7", 4.0, 1434.55));
// array_push($lista_itens, new Item("item 8", 5.0, 145.55));
// array_push($lista_itens, new Item("item 9", 0.6, 335.64));
// array_push($lista_itens, new Item("item 10", 3.4, 12.00));

for ($i=0; $i < $quantidade_objetos; $i++) {
	// Nome, espaço / peso (kg) , valor R$ 
	array_push($lista_itens, 
		new Item("Item ".$i, frand(0, $peso_maximo_obj), frand(0, $valor_maximo_obj))
	);
}

$espacos = array();
$valores = array();
$nomes = array();

foreach ($lista_itens as $item) {
	array_push($espacos, $item->espaco);
	array_push($valores, $item->valor);
	array_push($nomes, $item->nome);
}


// print_r($data);
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
	// 'numero_pais' => $numero_pais,
	// 'numero_individuos' => $numero_individuos,
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
$soma = 0;

for ($i=0; $i < count($espacos); $i++) {
	if ($resultado[$i] == 1) {
		$arr = array(
			'nome' => $nomes[$i],
			'valor' => $valores[$i],
			'peso' => $espacos[$i]
		);
		array_push($r, $arr);
		$soma += $espacos[$i];
	}
}

if ($soma > $peso_maximo) {
	echo "Solução não encontrada";
} else {
	echo (json_encode($r));
}