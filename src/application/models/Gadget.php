<?php

namespace application\models;

use application\models\Template as Template;
use application\interfaces\iSaveable as iSaveable;
use application\interfaces\iObservable as iObservable;

class Gadget extends Template implements iSaveable, iObservable {
	
	protected $_url;

	public function __construct($url){
		$this->_url = $url;
		$this->_template = Config::instance()->sitePath . Config::instance()->tplPath . 'gadget.php';
	}
	
	public function render(){
		
	}

}