<?php

require_once '../inc/Game.php';

/**
 * Let use the standard 30 seconds 
 * to run the whole program,
 * not only to solve the game!
 */
set_time_limit(30);

$game = new Game();

$t1 = microtime(true);

try {
	if ($game->solve()) {
		print "Solution found in ".(microtime(true)-$t1)." [s]<br />";
		$game->printSolution();
		$game->save();
	} else {
		print "No solution!";
	}
} catch (Exception $e) {
	echo 'FATAL ERROR: ',  $e->getMessage(), "\n";
}

?>