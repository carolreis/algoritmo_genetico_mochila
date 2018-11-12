<?php

class AlgoritmoGenetico {

	public $tamanho_populacao;
	public $populacao = array();
	public $geracao = 0;
	public $melhor_solucao = 0;
	public $lista_solucoes = array();
	public $lista_geracoes = array();

	function __construct($tamanho_populacao) {
		$this->tamanho_populacao = $tamanho_populacao;
	}

	function inicializa_populacao($espacos, $valores, $limite) {
		for ($i = 0; $i < $this->tamanho_populacao; $i++) {
			array_push($this->populacao, new Individuo($espacos, $valores, $limite));
		}
		$this->melhor_solucao = $this->populacao[0];
	}
 
    function ordena_populacao() {
		foreach ($this->populacao as $individuo) {
			$notas[] = $individuo->nota_avaliacao;
		}
		
		$notas_novas = quick_sort($notas);
		foreach ($notas_novas as $nota) {
			foreach ($this->populacao as $individuo) {
				if ($individuo->nota_avaliacao == $nota) {
					$populacao_nova[] = $individuo;
				}
			}
		}
		
		$this->populacao = $populacao_nova;
	
	}

	function define_melhor_solucao($individuo) {
		if ($individuo->nota_avaliacao >  $this->melhor_solucao->nota_avaliacao) {
			$this->melhor_solucao = $individuo;
		}
	}

	function soma_avaliacoes() {
		$soma = 0;
		foreach ($this->populacao as $individuo) {
			$soma += $individuo->nota_avaliacao;
		}
		return $soma;
	}

	function seleciona_pai($soma_avaliacao) {
		$pai = -1;
		$valor_sorteado = random() * $soma_avaliacao;
		$soma = 0;
		$i = 0;
		while ($i < $this->tamanho_populacao && $soma < $valor_sorteado) {
			$soma += $this->populacao[$i]->nota_avaliacao;
			$pai += 1;
			$i += 1;
		}

		return $pai;
	}

	function resolver($taxa_mutacao, $numero_geracoes, $espacos, $valores, $limite) {
		
		$this->inicializa_populacao($espacos, $valores, $limite);

		foreach ($ag->populacao as $individuo) {
			$individuo->avaliacao();
		}

		$this->ordena_populacao();
		$this->define_melhor_solucao($this->populacao[0]); 

		for ($i = 0; $i < $numero_geracoes; $i++) {

			$soma = $this->soma_avaliacoes();
			$nova_populacao = array();

			for ($i=0; $i < ($tamanho_populacao / 2); $i++) {
				$pai1 = $this->seleciona_pai($soma);
				$pai2 = $this->seleciona_pai($soma);
		
				$filhos = $this->populacao[$pai1]->crossover($this->populacao[$pai2]);

				$nova_populacao[] = $filhos[0]->mutacao($taxa_mutacao);
				$nova_populacao[] = $filhos[1]->mutacao($taxa_mutacao);
			}

			$this->populacao = $nova_populacao;

			foreach ($this->populacao as $individuo) {
				$individuo->avaliacao();
			}
			

			$this->ordena_populacao();
			$this->define_melhor_solucao($this->populacao[0]);
			$this->soma_avaliacoes();

	    }

	    return $this->melhor_solucao->cromossomo;
	}

}
