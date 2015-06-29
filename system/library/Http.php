<?php
if(!defined('SYSTEM_PATH')) exit('Access Denied');

/**
 * Http Class
 * 封装HTTP操作，依赖cURL
 */
class Http{
	private $handle;	//cURL句柄
	private $timeout = 20;	//HTTP请求超时时间

	public function __construct(){
		$this->handle = curl_init();
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($this->handle, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT_MS, $this->timeout * 500);
		curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->handle, CURLOPT_HEADER, 0);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, TRUE);
	}
	
	/**
	 * 设置来源URL
	 */
	public function setReferer($url){
		curl_setopt($this->handle, CURLOPT_REFERER, $url);
	}
	
	/**
	 * 设置超时时间
	 */
	public function setTimeout($second=20){
		$this->timeout = $second;
		curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT_MS, $this->timeout * 500);
		curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
	}
	
	/**
	 * 忽略之前保存的 Cookie
	 */
	public function newSession(){
		curl_setopt($this->handle, CURLOPT_COOKIESESSION, TRUE);
	}

	/**
	 * @param $cookie
	 */
	public function setCookie($cookie){
		curl_setopt($this->handle, CURLOPT_COOKIE, $cookie);
	}
	
	/**
	 * 用Get方法请求数据
	 */
	public function get($url){
		curl_setopt($this->handle, CURLOPT_HTTPGET, TRUE);
		curl_setopt($this->handle, CURLOPT_URL, $url);
		return curl_exec($this->handle);
	}
	
	/**
	 * 提交数据到指定的URL
	 */
	public function post($url, $data){
		curl_setopt($this->handle, CURLOPT_POST, TRUE);
		curl_setopt($this->handle, CURLOPT_URL, $url);
		curl_setopt($this->handle, CURLOPT_POSTFIELDS, $data);
		return curl_exec($this->handle);
	}
	
	public function setopt($option, $value){
		curl_setopt($this->handle, $option, $value);
	}

	public function errno(){
		curl_errno($this->handle);
	}
	
	public function error(){
		return curl_error($this->handle);
	}

	public function close(){
		curl_close($this->handle);
	}
}