<?php

require_once 'Board.php';

class Pagoda {

	/*
	private $pagoda = array(
		array(null, null, -0.3, 0.4, 0, null, null),
		array(null, null, 1, 0, 1, null, null),
		array(null, null, 1, 0, 1, null, null),
		array(0.5, 0, 0.5, 0.4, 0.1, 0.3, -0.1),
		array(0, 0.9, 0.7, 0.3, 0.9, 1.1, 0.4),
		array(null, null, 0.8, 0, 0.8, null, null),
		array(null, null, 0, 0.5, -0.2, null, null),
	);
	*/
	
	private $pagoda = array(
		array(null, null, 11, 8, 3, null, null),
		array(null, null, 7, 5, 2, null, null),
		array(4, 0, 4, 3, 1, 2, -1),
		array(3, 0, 3, 2, 1, 1, 0),
		array(1, 0, 1, 1, 0, 1, -1),
		array(null, null, 2, 1, 1, null, null),
		array(null, null, -1, 0, -1, null, null),
	);	
	
	public function __construct($pagoda = null) {
		if (!is_null($pagoda)) $this->pagoda = $pagoda;
	}
	
	public function calcPagoda(Board $board) {
		$result = 0;
		for ($x = 0; $x < $board->getWidth(); $x++) {
			for ($y = 0; $y < $board->getHeight(); $y++) {	
				if ($board->isOccupied($x, $y) && !is_null($this->pagoda[$x][$y])) {
					$result += $this->pagoda[$x][$y];
				}
			}
		}
		return $result;
	}
	
}

?>