<?php

require_once 'IAlgorithm.php';
require_once 'Solution.php';

/**
 * Backtracking and Pruning algorithm class
 *
 */
class Backtracking implements IAlgorithm {
	
	/**
	 * The algorithm will end after 32 valid movements
	 */
	const STEPS = 32;		
	
	/**
	 * Board instance where the algorithm will move the pegs
	 * 
	 * @var Board
	 */
	private $board;
	
	/**	 
	 * Array to hold the boards already visited and discarded
	 * 
	 * @var array
	 */
	private $oldBoards = array();
	
	/**	 
	 * Solution instance
	 * 
	 * @var Solution
	 */
	private $solution;	
	
	public function __construct($board) {
		$this->board = $board;
		$this->solution = new Solution();
		$this->solution->setSolution(0, $board);		
	}
	
	/**
	 * This could be refactored and move it to an abstract class
	 * in the future if is needed, so every algortihm could share it
	 */
	public function getSolution() {		
		return $this->solution;
	}
	
	/**
	 * The solve() method calls findSolution() for the first move and it gets the valid
	 * directions to try the movements from the board object.
	 * I implemented two ways to read the board and the algoritm decides between them
	 * randomly
	 *
	 * @return boolean
	 */
	public function solve() {
		$this->directions = $this->board->getDirections();
		return $this->{'findSolution'.(rand(1,2))}(1);
	}
	
	/**
	* Backtracking algorithm implemented with a recursive function
	* There are many possible ways to walk through the board (secuencials, random, etc)
	* Here it starts from the upper left corner and it moves down and to the right.
	* 
	* @param move Current number of move
	*/
	private function findSolution1($move) {
		for ($x = 0; $move < self::STEPS && $x < $this->board->getWidth(); $x++) {
			for ($y = 0; $y < $this->board->getHeight(); $y++) {
				if ($this->tryMove($move, $x, $y, __FUNCTION__)) return true;
			}                       
		}
		return false;
	}
	
	/**	 
	 * This is another way to read the board and make decisions.
	 * 
	 * @param move Current number of move
	 */
	private function findSolution2($move) {
		for ($y = ($this->board->getHeight() - 1); $move < self::STEPS && $y >= 0; $y--) {
			for ($x = ($this->board->getWidth() - 1); $x >= 0 ; $x--) {			
				if ($this->tryMove($move, $x, $y, __FUNCTION__)) return true;
			}                       
		}
		return false;
	}	
	
	/**
	 * Try to move the peg in (x,y). 
	 * It tries the directions in the order defined by the board object. 
	 * The order we read the directions can be changed in the future 
	 * implementing some kind of heuristic.
	 *
	 * @param int $move Move number
	 * @param int $x Peg's row
	 * @param int $y Peg's column
	 * @param string $recursive Function to call to try the next move
	 * @return boolean
	 */						
	private function tryMove($move, $x, $y, $recursive) {		
		foreach ($this->directions as $direction) {
			if ($this->board->jump($x, $y, $direction)) {				
				$this->solution->setSolution($move, $this->board);																
				if (!($move >= self::STEPS-1 && $this->board->isOccupied(3, 3))) {													
					if ((empty($this->oldBoards) || !in_array($this->board->hash(), $this->oldBoards))
						&& $this->{$recursive}($move + 1))
					{						
						return true;
					} else {
						$this->oldBoards[] = $this->board->hash();										
						$this->board->jumpBack($x, $y, $direction);
					}
				} else {
					return true;
				}
			}
		}
		return false;
	}
	
}

