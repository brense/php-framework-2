<?php

namespace application\models;

use application\models\AbstractModel as AbstractModel;

class Request extends AbstractModel {
	
	protected $_uri;
	protected $_params;
	protected $_method;
	protected $_useragent;
	protected $_referer;
	protected $_timestamp;
	protected $_ip;

	public function __construct(){
		// sanitize the requested uri
		$uri = str_replace(Config::instance()->rootUrl, '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		$arr = explode('?', $uri);
		$uri = array_shift($arr);
		if(substr($uri, -1, 1) == '/'){
			$uri = substr($uri, 0, -1);
		}
		// find the requesters ip
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		// set the request object variables
		if(isset($_SERVER['REQUEST_METHOD'])) {
			$this->_uri = $uri;
			$this->_params = $_REQUEST;
			$this->_method = $_SERVER['REQUEST_METHOD'];
			$this->_useragent = $_SERVER['HTTP_USER_AGENT'];
			$this->_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$this->_timestamp = $_SERVER['REQUEST_TIME'];
			$this->_ip = trim($ip);
		}
	}

}