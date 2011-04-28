<?php

require_once 'Solution.php';

/**
 * Backtracking algorithm class
 *
 */
class Backtracking {
	
	const RIGHT = 0;
	const TOP = 1;
	const LEFT = 2;
	const BOTTOM = 3;
	
	const STEPS = 32;
	
	/**
	 * A 100% pure backtracking method will take a lot of time,
	 * so we give some heuristic to the algorithm. It will
	 * try to move each peg in a defined order and this array
	 * store all the possible orders.
	 *
	 * @var array
	 */
	static private $orders = array(
		'RT' => array(self::RIGHT, self::TOP, self::LEFT, self::BOTTOM),
		'LT' => array(self::LEFT, self::TOP, self::RIGHT, self::BOTTOM),
		'RB' => array(self::RIGHT, self::BOTTOM, self::LEFT, self::TOP),
		'LB' => array(self::LEFT, self::BOTTOM, self::RIGHT, self::TOP),
		'TR' => array(self::BOTTOM, self::LEFT, self::TOP, self::RIGHT),
		'TL' => array(self::BOTTOM, self::RIGHT, self::TOP, self::LEFT),
		'BR' => array(self::TOP, self::LEFT, self::BOTTOM, self::RIGHT),
		'BL' => array(self::TOP, self::RIGHT, self::BOTTOM, self::LEFT),
	);
	
	static private $secuencials = array('RT', 'LT', 'RB', 'LB', 'TR', 'TL', 'BR', 'BL');
	
	/**
	 * Board instance where the algorithm will move the pegs
	 *
	 * @var Board
	 */
	private $board;
	
	private $directions = array();
	
	private $solution;
	
	public function __construct($board) {
		$this->board = $board;
		$this->solution = new Solution();
		$this->solution->setSolution(0, $board);
	}
	
	public function getSolution() {
		return $this->solution;
	}
	
	/**
	 * We can read the board in many different ways.
	 * Here the algorithm choose between a few secuencial ways
	 * and applied the heuristic so a solution is found in a
	 * reasonable amount of time.
	 *
	 * @return boolean
	 */
	public function solve() {
		$secuencial = self::$secuencials[rand(0, count(self::$secuencials) - 1)];
		$this->directions = self::$orders[$secuencial];		
		return $this->{'findSolution'.$secuencial}(1);
	}
	
	/**
	* Backtracking algorithm implemented with a recursive function
	* 
	* @param move current number of move
	*/
	private function findSolutionRT($move) {
		for ($x = 0; $move < self::STEPS && $x < $this->board->getWidth(); $x++) {
			for ($y = 0; $y < $this->board->getHeight(); $y++) {
				if ($this->tryMove($move, $x, $y, 'findSolutionRT')) return true;
			}                       
		}
		return false;
	}
	
	private function findSolutionLT($move) {
		for ($x = ($this->board->getWidth() - 1); $move < self::STEPS && $x >= 0; $x--) {
			for ($y = 0; $y < $this->board->getHeight(); $y++) {
				if ($this->tryMove($move, $x, $y, 'findSolutionLT')) return true;
			}                       
		}
		return false;
	}
	
	private function findSolutionRB($move) {
		for ($x = 0; $move < self::STEPS && $x < $this->board->getWidth(); $x++) {
			for ($y = ($this->board->getHeight() - 1); $y >= 0; $y--) {
				if ($this->tryMove($move, $x, $y, 'findSolutionRB')) return true;
			}                       
		}
		return false;
	}
	
	private function findSolutionLB($move) {
		for ($x = ($this->board->getWidth() - 1); $move < self::STEPS && $x >= 0; $x--) {
			for ($y = ($this->board->getHeight() - 1); $y >= 0; $y--) {
				if ($this->tryMove($move, $x, $y, 'findSolutionLB')) return true;
			}
		}
		return false;
	}
	
	private function findSolutionTR($move) {
		for ($y = 0; $move < self::STEPS && $y < $this->board->getHeight(); $y++) {
			for ($x = 0; $x < $this->board->getWidth(); $x++) {			
				if ($this->tryMove($move, $x, $y, 'findSolutionTR')) return true;
			}                       
		}
		return false;
	}
	
	private function findSolutionTL($move) {
		for ($y = 0; $move < self::STEPS && $y < $this->board->getHeight(); $y++) {
			for ($x = ($this->board->getWidth() - 1); $x >= 0; $x--) {
				if ($this->tryMove($move, $x, $y, 'findSolutionTL')) return true;
			}                       
		}
		return false;
	}
	
	private function findSolutionBR($move) {
		for ($y = ($this->board->getHeight() - 1); $move < self::STEPS && $y >= 0; $y--) {
			for ($x = 0; $x < $this->board->getWidth(); $x++) {
				if ($this->tryMove($move, $x, $y, 'findSolutionBR')) return true;
			}                       
		}
		return false;
	}
	
	private function findSolutionBL($move) {
		for ($y = ($this->board->getHeight() - 1); $move < self::STEPS && $y >= 0; $y--) {
			for ($x = ($this->board->getWidth() - 1); $x >= 0; $x--) {
				if ($this->tryMove($move, $x, $y, 'findSolutionBL')) return true;
			}
		}
		return false;
	}
	
	/**
	 * Try to move the peg in (x,y). 
	 * It tries the directions in the order defined by the
	 * heuristic.
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
					if ($this->{$recursive}($move + 1)) {
						return true;
					} else {										
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

?>