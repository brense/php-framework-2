<?php

namespace application\models;

use application\models\Template as Template;
use application\interfaces\iSaveable as iSaveable;
use application\interfaces\iObservable as iObservable;

class Widget extends Template implements iSaveable, iObservable {
	
	public function render(){
		
	}

}