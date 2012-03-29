<?php

namespace application\interfaces;

use application\interfaces\iObservable as iObservable;

interface iView {
	
	public function setModel(iObservable $model);
	public function getModel();
	
	public function setController(iController $controller = null);
	public function getController();
	
	public function defaultController(iObservable $model);
	
	public function render(Array $options = array());
	
}