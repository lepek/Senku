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
	
	public function save() {
		mysql_connect("localhost", "root", "apache23") or die(mysql_error());
		mysql_select_db("game") or die(mysql_error());
		mysql_query('INSERT INTO `solution` (`created`) VALUES (NOW())') or die(mysql_error());
		foreach ($this->solutions as $move => $solution) {
			mysql_query("INSERT INTO `board` (`move`, `solution_id`) VALUES ('{$move}', '1')") or die(mysql_error());
			for ($x = 0; $x < $solution->getWidth(); $x++) {
				for ($y = 0; $y < $solution->getHeight(); $y++) {
					if ($solution->isOccupied($x, $y)) {
						mysql_query("INSERT INTO `peg` (`x`, `y`, `board_id`) VALUES ('{$x}', '{$y}', '1')") or die(mysql_error());
					}
				}
			}
		}
	}
	
}

?>