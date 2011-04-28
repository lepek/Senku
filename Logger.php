<?php

class Logger {
	
	public static function show($label, $var=null) {
		return;
		print '<pre>';
		print $label.': ';
		if (!is_null($var)) print_r($var);
		print '</pre>';
	}
	
}

?>