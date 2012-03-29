<?php

namespace application\views;

use application\interfaces\iObserver as iObserver;
use application\interfaces\iObservable as iObservable;
use application\interfaces\iController as iController;
use application\interfaces\iView as iView;

abstract class AbstractView implements iObserver, iView {
	
	protected $_model;
	protected $_controller;
	
	protected $_events = array();
	private $_types = array('click', 'doubleclick', 'mouseover', 'mouseout');
	private $_children = array();
	protected $_template;
	protected $_vars;
	
	public function __contstruct(iObservable $model, iController $controller = null){
		$this->setModel($model);
		if(isset($controller)){
			$this->setController($controller);
		}
		// TODO in the database there must be a table where all observers of all models are stored with their $this->_vars['id'] ???
	}
	
	public function setModel(iObservable $model){
		$this->_model = $model;
	}
	
	public function getModel(){
		return $this->_model;
	}
	
	public function setController(iController $controller = null){
		$this->_controller = $controller;
	}
	
	public function getController(){
		if(!isset($this->_controller)){
			$this->setController($this->defaultController($this->getModel()));
		}
		return $this->_controller;
	}
	
	public function defaultController(iObservable $model){
		return null;
	}
	
	public function addChild(AbstractView $view){
		$this->_children[] = $view;
	}
	
	public function removeChild(AbstractView $view){
		$key = array_search($view, $this->_children, true);
		if($key){
			unset($this->_children[$key]);
		}
	}
	
	public function Children(){
		return $this->_children;
	}
	
	public function addEventListener($event, $action){
		if(in_array($event, $this->_types)){
			$this->_events[$event] = $action;
		} else {
			throw new \Exception('Incorrect event type: ' . $event);
		}
	}
	
	public function removeEventListener($event, $action = null){
		if(isset($this->_events[$event]) && ($this->_events[$event] == $action || !isset($action))){
			unset($this->_events[$event]);
		}
	}
	
	public function render(Array $options = array()){
		if(isset($this->_vars['id'])){
			$contents;
			ob_start();
			include($template);
			$contents = ob_get_contents();
			ob_end_clean();
			
			if(count($this->_events) > 0){
				$contents .= '<script type="text/javascript">';
				foreach($this->_events as $event => $action){
					if(is_array($action)){
						$action = implode('/', $action);
					}
					$contents .= "$('#" . $this->_vars['id'] . "').bind(" . $event . ", function(e){
	e.preventDefault();
	var rel;
	var href;
	if($(this).rel() != undefined){rel = '&rel='+$(this).rel();}
	if($(this).href() != undefined){href = '&href='+$(this).href();}
	$.get('view/" . $action . "/?id=" . $this->_vars['id'] . "'+rel+href, function(data){
		$.each(data, function(index, view){
			$(view.selector).html(view.html);
		});
	});
});";
				}
				$contents .= '</script>';
			}
			return $contents;
		} else {
			return false;
		}
	}
	
	public function update(iObservable $observable, $changes){
		if(isset($this->_vars['id'])){
			return array('selector' => '#' . $this->_vars['id'], 'html' => $this->render());
		}
	}
	
}