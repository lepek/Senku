<?php

/**
 * Abstract class to represent a valid position in the board
 *
 */
abstract class Hole {
	protected $symbol;
	
	public function __toString() {
		return $this->symbol;
	}
	
}