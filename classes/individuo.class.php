<?php

class Individuo {

	public $espacos;
	public $valores;
	
	public $peso_maximo;
	public $peso_maximo_obj;
	public $peso_min_obj;
	public $valor_maximo_obj;
	public $valor_min_obj;

	public $nota_avaliacao;
	public $espaco_usado;
	public $geracao;
	public $cromossomo = array();
	
	function __construct(
		$espacos, 
		$valores, 
		$peso_maximo, 
		$peso_maximo_obj, 
		$peso_min_obj, 
		$valor_maximo_obj,
		$valor_min_obj, 
		$geracao = 0, 
		$cromossomo = array()
	) {
		$this->espacos = $espacos; // array 
		$this->valores = $valores; // array 

		$this->peso_maximo = $peso_maximo; // float
		$this->peso_maximo_obj = $peso_maximo_obj; // float
		$this->peso_min_obj = $peso_min_obj; // float
		$this->valor_maximo_obj = $valor_maximo_obj; // float
		$this->valor_min_obj = $valor_min_obj; // float

		$this->nota_avaliacao = 0;
		$this->espaco_usado = 0; 
		$this->geracao = $geracao; // int

		// gera cromossomo aleatoriamente
		for ($i = 0; $i < count($this->espacos); $i++) {
			if ( random() < 0.5 ) {
				array_push($this->cromossomo, 0);
			} else {
				array_push($this->cromossomo, 1);
			}
		}

	}

	function avaliacao() {
		$nota = 0;
		$soma_espacos = 0;

		for ($i = 0; $i < count($this->cromossomo); $i++) {
			if($this->cromossomo[$i] == 1) {
				if(
					$this->espacos[$i] < $this->peso_maximo_obj || 
					$this->espacos[$i] > $this->peso_min_obj ||
					$this->valores[$i] < $this->valor_maximo_obj ||
					$this->valores[$i] > $this->valor_min_obj

				) {
					$nota += $this->valores[$i];
					$soma_espacos += $this->espacos[$i];
				}
			}
		}
		
		if ($soma_espacos > $this->peso_maximo) {
			$nota = 1;			
		}

		$this->nota_avaliacao = $nota;
		$this->espaco_usado = $soma_espacos;
	}

	function crossover($outro_individuo) {
		$corte = round(random() * count($this->cromossomo));
		
		$filho1 = array_merge(array_slice($outro_individuo->cromossomo, 0, $corte), array_slice($this->cromossomo, $corte));
		$filho2 = array_merge(array_slice($this->cromossomo, 0, $corte), array_slice($outro_individuo->cromossomo, $corte));

		$filhos = array(
			new Individuo(
				$this->espacos, 
				$this->valores, 
				$this->peso_maximo, 
				$this->peso_maximo_obj, 
				$this->peso_min_obj, 
				$this->valor_maximo_obj,
				$this->valor_min_obj, 
				$this->geracao + 1,
				$this->cromossomo = $filho1
			),
			new Individuo(
				$this->espacos, 
				$this->valores, 
				$this->peso_maximo, 
				$this->peso_maximo_obj, 
				$this->peso_min_obj, 
				$this->valor_maximo_obj,
				$this->valor_min_obj, 
				$this->geracao + 1,
				$this->cromossomo = $filho2
			)
		);

		return $filhos;
	}

	function mutacao($taxa_mutacao) {
		for ($i = 0; $i < count($this->cromossomo); $i++) {
			if (random() < $taxa_mutacao) {
				if ($this->cromossomo[$i] == 1) {
					$this->cromossomo[$i] = 0;
				} else {
					$this->cromossomo[$i] = 1;
				}
			}
		}

		return $this;
	}

}