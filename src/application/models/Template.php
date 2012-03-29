<?php

namespace application\models;

use application\models\AbstractModel as AbstractModel;
use application\interfaces\iSaveable as iSaveable;
use application\interfaces\iObservable as iObservable;

class Template extends AbstractModel implements iSaveable, iObservable {
	
	protected $_template;
	protected $_vars = array();

	public function __construct($template, Array $vars = array()){
		if(file_exists($this->_template = Config::instance()->appPath . Config::instance()->tplPath . $template . '.php')){
			$this->_template = Config::instance()->appPath . Config::instance()->tplPath . $template . '.php';
		} else {
			$this->_template = Config::instance()->apaPath . Config::instance()->tplPath . $template . '.php';
		}
		$this->_vars = $vars;
	}
	
	public function render(){
		include($this->_template);
	}
	
	public function setVar($name, $value){
		$this->_vars->$name = $value;
	}
	
	public function getVar($name){
		return $this->_vars->$name;
	}
	
	public function __get($property){
		switch($property){
			case 'vars':
				return $this->_vars;
				break;
		}
	}
	
	public function __set($property, $value){
		switch($property){
			case 'vars':
				$this->_vars = $value;
				break;
		}
	}

}