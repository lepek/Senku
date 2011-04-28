<?php

/**
 * English Peg solitare solution
 *
 */
class Solution {
	
	/**
	 * The solution is given as a sequence of board situations
	 *
	 * @var array
	 */
	private $solutions = array();
	
	/**
	 * Save a copy of a board instance to persist it as
	 * a step for the final solution
	 *
	 * @param int $move
	 * @param Board $board
	 */
	public function setSolution($move, Board $board) {
		$this->solutions[$move] = clone $board;
	}
	
	/**
	 * Iterates through the <code>solutions</code> array
	 * and show each board in it.
	 *
	 */
	public function show() {
		foreach ($this->solutions as $solution) {
			$solution->show();
		}
	}
	
}

?>