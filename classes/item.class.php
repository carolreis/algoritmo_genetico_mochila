<?php

class Item {

	public $nome;
	public $espaco;
	public $valor;

	function __construct($nome, $espaco, $valor) {
		$this->nome = $nome;
		$this->espaco = $espaco;
		$this->valor = $valor;
	}

}