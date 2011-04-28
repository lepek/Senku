<?php

require_once 'Board.php';

/**
 * English Peg solitare game
 *
 */
class Game {
	
	private $board;
	
	private $solution;
	
	public function __construct() {
		$this->board = new Board();
	}
	
	/**
	 * Solve the game. It tries to solve the game and
	 * set the solution
	 *
	 * @param string $method Algorithm to be used to solve the game
	 * @return boolean
	 * 
	 * @todo Add more algorithms to solve the game
	 * 
	 */
	public function solve($method = null) {
		switch ($method) {
			default:
				require_once 'Backtracking.php';
				$method = new Backtracking($this->board);
				break;
		}
		if ($method->solve()) {
			$this->solution = $method->getSolution();
			return true;
		}
		return false;
	}
	
	/**
	 * Show the solution in a HTML format
	 * 
	 * @todo Add more output formats
	 * @todo Manage the use case when this method is called 
	 * and there is no solution
	 *
	 */
	public function printSolution() {
		if (is_a($this->solution, 'Solution')) {
			$this->solution->show();
		}
	}
	
}

?>