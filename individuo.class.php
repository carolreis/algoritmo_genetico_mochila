<?php

class Individuo {

	public $espacos;
	public $valores;
	public $limite;
	public $nota_avaliacao;
	public $espaco_usado;
	public $geracao;
	public $cromossomo = array();

	function __construct($espacos, $valores, $limite, $geracao = 0, $cromossomo = array()) {
		$this->espacos = $espacos; // array 
		$this->valores = $valores; // array 
		$this->limite = $limite; // int
		$this->nota_avaliacao = 0;
		$this->espaco_usado = 0; 
		$this->geracao = $geracao; // int

		// gera cromossomo aleatoriamente
		for ($i = 0; $i < count($espacos); $i++) {
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
				$nota += $this->valores[$i];
				$soma_espacos += $this->espacos[$i];
			}
		}
		
		if ($soma_espacos > $this->limite) {
			$nota = 1;			
		}

		$this->nota_avaliacao = $nota;
		$this->espaco_usado = $soma_espacos;
	}

	function crossover($outro_individuo) {
		$corte = round(random() * count($this->cromossomo));
		
		$filho1 = array_merge(array_slice($outro_individuo->cromossomo, 0, $corte), array_slice($this->cromossomo, $corte + 1));


		$filho2 = array_merge(array_slice($this->cromossomo, 0, $corte), array_slice($outro_individuo->cromossomo, $corte + 1));

		$filhos = array(
			new Individuo(
				$this->espacos,
				$this->valores,
				$this->limite,
				$this->geracao + 1,
				$this->cromossomo = $filho1
			),
			new Individuo(
				$this->espacos,
				$this->valores, 
				$this->limite,
				$this->geracao + 1,
				$this->cromossomo = $filho
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