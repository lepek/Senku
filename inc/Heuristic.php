<?php

require_once 'IAlgorithm.php';
require_once 'Solution.php';

/**
 * 
 * Dummy class to show how we can implement a design
 * that gives us the posibility to use different algorithms
 * to solve the game
 *
 */
class Heuristic implements IAlgorithm {
	
	/**
	 * Use the heuristic to solve the game 
	 * and set the solution
	 */	
	public function solve() {
		return true;
	}
	
	public function getSolution() {
		throw new Exception('Heuristic algorithm is not implemented yet!');    
	}

}