<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

class Controller{
	private $data = array();

	public function assign($name, $value){
		$this->data[$name] = $value;
	}

	public function display($filename){
		foreach($this->data as $key => $value){
			$$key = $value;
		}
		require_once APP_PATH."view/".$filename.".html";
	}

	public function before(){}
	public function after(){}
}