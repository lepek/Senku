<?php

require_once 'Board.php';
require_once 'DB.php';

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
	
	public function __construct($solutions = array()) {
		if (!empty($solutions)) $this->solutions = $solutions; 
	}
	
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
	
	/**	 
	 * Persists the solution instance in the database.
	 * This is a normalized DB schema, probably for better
	 * performance in a real scenario it would be good
	 * to use another schema to gain performance
	 * 
	 * @todo Check the return values of the DB class and
	 * do the appropiate actions (return values, exceptions, etc)
	 */
	public function save() {
		$db = DB::getInstance();
		$db->query('INSERT INTO `solution` (`created`) VALUES (NOW())');		
		$solution_id = $db->lastInsertId();		
		foreach ($this->solutions as $move => $solution) {
			$db->query("INSERT INTO `board` (`move`, `solution_id`) 
				VALUES ('{$move}', '{$solution_id}')");
			$board_id = $db->lastInsertId();			
			for ($x = 0; $x < $solution->getWidth(); $x++) {
				for ($y = 0; $y < $solution->getHeight(); $y++) {
					if ($solution->isOccupied($x, $y)) {
						$db->query("INSERT INTO `peg` (`x`, `y`, `value`, `board_id`) 
							VALUES ('{$x}', '{$y}', '1', '{$board_id}')");
					} else if ($solution->isBlank($x, $y)) {
						$db->query("INSERT INTO `peg` (`x`, `y`, `value`, `board_id`) 
							VALUES ('{$x}', '{$y}', '0', '{$board_id}')");
					}
				}
			}
		}
	}
	
	/**
	 * Get a Solution instance from the DB.
	 * It use the primary key to get the solution, then
	 * we can add get the solution by the date or other params
	 * 
	 * @param int $id Primary key of the solution we want to build
	 * 
	 * @todo Check the return values of the DB class and
	 * do the appropiate actions (return values, exceptions, etc)
	 * 
	 * @todo Remove hardcoded values
	 */
	public static function getSolutionById($id) {
		$db = DB::getInstance();
		$db->query("SELECT * FROM `solution`, `board`, `peg` 
			WHERE `solution`.`id` = `board`.`solution_id` 
			AND `board`.`id` = `peg`.`board_id` 
			AND `solution`.`id` ='{$id}'");
		
		/**
		 * I don't like this hardcoded numbers, this should come from the board
		 * configuration, maybe could be store in the board table. For now
		 * I will leave them but I *really* don't like them in the Solution class
		 */
		$encodedBoards = array_fill(0, 32, array_fill(0, 7, array_fill(0, 7, null)));				
		
		while ($row = $db->fetchRow()) {			
			$encodedBoards[$row['move']][$row['x']][$row['y']] = $row['value'];
		}

		$solutions = array();
		foreach ($encodedBoards as $encodedBoard) {
			$solutions[] = new Board($encodedBoard);
		}
		
		return new Solution($solutions);
		
	}	
	
}

?>