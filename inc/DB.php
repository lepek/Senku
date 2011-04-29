<?php

/**
 * 
 * *VERY BASIC* Singleton class to connect and query the game's db
 * @todo Improve the poor error handling
 * @todo Implement security against sql-injection
 * @todo Fix return values
 *
 */
class DB {
	
	private $query;
	
	private $results;
	
	private $link;

    private static $database;
        
    private function __construct() {
		$data = $this->getData();
		$this->link = mysql_connect($data['dbhost'], $data['dbuser'], $data['dbpass']) or die(mysql_error());
		mysql_select_db($data['dbname']) or die(mysql_error());
	}
        
    public static function getInstance() {
		if (!(self::$database instanceof DB)) {
			self::$database = new DB();
		}
    	return self::$database;
	}
    
    public function query($query) {
		$this->query = $query;
		$this->execute();
	}        
        
    public function lastInsertId() {
		return mysql_insert_id();
	}
        
    public function fetchRow() {
		return mysql_fetch_assoc($this->results);
	}
	
    private function execute() {
		$this->results = mysql_query($this->query, $this->link) or die(mysql_error());
    }
    
    /**
     * 
     * This should read from a config file
     * or env vars or whatever.
     */
	private function getData() {
		return array(
			'dbhost' => 'localhost', 
			'dbuser' => 'root',
			'dbpass' => 'apache23',
			'dbname' => 'game',
		);
	}    
} 