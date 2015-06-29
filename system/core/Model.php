<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Model{

	public $db;
	
	/*
	 * 构造函数
	 */
	public function __construct(){
	    $databaseConfig = load_config('database');
	    if(!isset($this->database)){
	        $currentDBConfig = $databaseConfig['default'];
	    }else{
	        $currentDBConfig = $databaseConfig[$this->database];
	    }

		$this->db = new DataBase($currentDBConfig);
	}
	
}