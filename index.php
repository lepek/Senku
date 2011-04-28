<?php

require_once 'inc/Game.php';

/**
 * 60 seconds is a reasonable time to find a
 * solution for the game
 */
set_time_limit(60);

$game = new Game();

$t1 = microtime(true);

if ($game->solve()) {
	print "Solution found in ".(microtime(true)-$t1)." [s]<br />";
	$game->printSolution();
} else {
	print "No solution found!?";
}

?>