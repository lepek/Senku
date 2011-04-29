<?php

abstract class Hole {
	protected $symbol;
	
	public function __toString() {
		return $this->symbol;
	}
	
}