<?php

require_once 'Peg.php';
require_once 'Blank.php';

/**
 * The board of the English Peg Solitare
 *
 */
class Board {

	/**
	 * Constants to represent the directions
	 *
	 */
	const RIGHT = 0;
	const TOP = 1;
	const LEFT = 2;
	const BOTTOM = 3;	
	
	/**
	* The game board with initial position of all pegs for a new standard game
	*/	
	private $board = array(
		array(null, null, 1, 1, 1, null, null),
		array(null, null, 1, 1, 1, null, null),
		array(1, 1, 1, 1, 1, 1, 1),
		array(1, 1, 1, 0, 1, 1, 1),
		array(1, 1, 1, 1, 1, 1, 1),
		array(null, null, 1, 1, 1, null, null),
		array(null, null, 1, 1, 1, null, null),		
	);
	
	public function __construct($encodedBoard = null, $opts = array()) {					
		if (!isset($opts['width'])) $opts['width'] = 7;
		if (!isset($opts['height'])) $opts['height'] = 7;
		
		if (is_null($encodedBoard)) $encodedBoard = $this->board;
		$this->board = $this->build($encodedBoard, $opts['width'], $opts['height']);
	}

	/**
	 * From a two-dimension array encoded with 1s and 0s
	 * we build the board. With this we give the posibility to
	 * use the class for different board configurations.
	 * 
	 * @param array $encodedBoard
	 * @param int $width
	 * @param int $height
	 */
	public function build($encodedBoard, $width, $height) {
		$board = array_fill(0, $width, array_fill(0, $height, null));
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $height; $y++) {				
				if ($encodedBoard[$x][$y] == 1) {
					$board[$x][$y] = new Peg();
				} else if (!is_null($encodedBoard[$x][$y])) {
					$board[$x][$y] = new Blank();
				}
			}
		}
		return $board; 
	}
	
	/**
	 * Valid directions to move through this board
	 */
	public function getDirections() {
		return array(self::RIGHT, self::TOP, self::LEFT, self::BOTTOM);
	}
	
	/**
	 * Is it possible to change the board size and keep
	 * using the same algorithms?
	 *
	 * @return int
	 */
	public function getWidth() {
		return count($this->board);
	}

	public function getHeight() {
		return count($this->board);
	}	
	
	public function clearField($x, $y) {
		$this->board[$x][$y] = new Blank();
	}

	public function setPeg($x, $y) {
		$this->board[$x][$y] = new Peg();
	}	
	
	/**
	 * Talking about performance is faster to store and search for a hash 
	 * since we need to compare only the board attribute to identify the board.
	 * Is not very OOP, but is almost 50% faster in this case.
	 * __compare() is not an option right now (http://bugs.php.net/bug.php?id=51875)
	 */	
	public function hash() {
		$hash = null;
		for ($x = 0; $x < $this->getWidth(); $x++) {
			for ($y = 0; $y < $this->getHeight(); $y++) {
				if ($this->board[$x][$y] instanceof Peg) {
					$hash .= 'P';  		
				} else {
					$hash .= 'B';
				}
			}
		}
		return $hash;
	}
	
	/**
	 * Jumps the peg from (x,y) over the neighbouring peg in the given <code>direction</code>
	 * and removes the peg we have jumped over. 
	 * Returns true if the move was according to the game rules and false otherwise.
	 * The game board only changes state if the move was valid.
	 * 
	 * @param int $x The row of the Peg to be moved
	 * @param int $y The column of the Peg to be moved
	 * @param int $direction The direction in which the Peg will be moved
	 * @return boolean
	 */
	public function jump($x, $y, $direction) {
		$newX = $this->getNewX($x, $direction);
		$newY = $this->getNewY($y, $direction);
		if ($this->isValidMove($x, $y, $newX, $newY)) {			
			$this->setPeg($newX, $newY);
			$this->clearField($x, $y);
			$this->clearField(($x + $newX) / 2, ($y + $newY) / 2);
			return true;
		}
		return false;
	}
	
	/**
	 * A peg "jumps back" and the previously removed peg is returned at
	 * its proper position.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $direction
	 */
	public function jumpBack($x, $y, $direction) {
		$newX = $this->getNewX($x, $direction);
		$newY = $this->getNewY($y, $direction);		
		$this->clearField($newX, $newY);
		$this->setPeg($x, $y);
		$this->setPeg(($x + $newX) / 2, ($y + $newY) / 2);
	}
	
	/**
	 * Prints out the contents of this board in HTML format
	 * 
	 * @todo Add more output formats
	 */
	public function show() {
		print "<br />\n\r";
		print '<table border="0">';
		for ($x = 0; $x < $this->getWidth(); $x++) {
			print "<tr>";
			for ($y = 0; $y < $this->getHeight(); $y++) {
				print '<td>'.$this->board[$x][$y].'</td>';
			}
			print "</tr>";
		}
		print "</table>";
		print "<br />\n\r";
	}
	
	/**
	 * Returns true if there is a peg at (x,y).
	 * 
	 */
	public function isOccupied($x, $y) {
		return ($this->board[$x][$y] instanceof Peg);
	}
	
	public function isBlank($x, $y) {
		return ($this->board[$x][$y] instanceof Blank);
	}

	/**
	 * Checks whether there is a peg at (x,y), an empty field at (newX, newY), 
	 * and a peg between both fields. This would mean that I can move the
	 * peg from (x,y) to (newX, newY)
	 *
	 * @param int $x Initial Peg's row
	 * @param int $y Initial Peg's column
	 * @param int $newX Final Peg's row
	 * @param int $newY Final Peg's column
	 * @return boolean
	 */
	private function isValidMove($x, $y, $newX, $newY) {		
		return (0 <= $x && $x < $this->getWidth() 
			&& 0 <= $y && $y < $this->getHeight()
			&& 0 <= $newX && $newX < $this->getWidth() 
			&& 0 <= $newY && $newY < $this->getHeight()
			&& ($this->board[$newX][$newY] instanceof Blank)
			&& $this->isOccupied(($x + $newX) / 2, ($y + $newY) / 2)
			&& $this->isOccupied($x, $y));
	}
	
	private function getNewX($x, $direction) {
		$newX = $x;
		switch ($direction) {
			case self::RIGHT: 
				$newX += 2;
				break;
			case self::LEFT: 
				$newX -= 2;
		}
		return $newX;
	}

	private function getNewY($y, $direction) {
		$newY = $y;

		switch ($direction) {
			case self::TOP: 
				$newY -= 2;
				break;
			case self::BOTTOM: 
				$newY += 2;
		}
	
		return $newY;
	}
	
}

