<?php

namespace application\controllers;

use application\interfaces\iController as iController;
use application\interfaces\iObservable as iObservable;
use application\interfaces\iObserver as iObserver;

abstract class AbstractController implements iController {
	
	protected $_model;
	protected $_view;
	
	public function __construct(iObservable $model){
		$this->setModel($model);
	}
	
	public function setModel(iObservable $model){
		$this->_model = $model;
	}
	
	public function getModel(){
		return $this->_model;
	}
	
	public function setView(iObserver $view){
		$this->_view = $view;
	}
	
	public function getView(){
		return $this->_view;
	}
	
}