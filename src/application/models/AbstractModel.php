<?php

namespace application\models;

use application\models\mappers\ObjectMapper as ObjectMapper;
use application\interfaces\iObserver as iObserver;

abstract class AbstractModel {
	
	protected $_id;
	protected $_observers = array();
	
	public function __construct(Array $args = array(), $create = true){
		if($create && count($args) > 0){
			$model = self::create($args);
			if($model->id > 0){
				$this->id = $model->id;
			}
		}
	}
	
	public static function __callStatic($function, Array $parameters){
		if(strpos($function, 'find_by_') == 0 && count($parameters) == 1){
			$key = str_replace('find_by_', '', $function);
			$value = implode($parameters);
			return self::find(array($key => $value));
		}
	}
	
	public static function create(Array $args = array()){
		$class = get_called_class();
		
		$model = new $class(array(), false);
		foreach($args as $key => $value){
			$model->$key = $value;
		}
		
		$mapper = new ObjectMapper();
		$mapper->create($model);
		return $model;
	}
	
	public static function find(Array $args = array()){
		$class = get_called_class();
		$mapper = new ObjectMapper($class);
		return $mapper->read($args);
	}
	
	public function save(){
		$class = get_class($this);
		$mapper = new ObjectMapper();
		$mapper->update($this);
	}
	
	public function delete(){
		$class = get_class($this);
		$mapper = new ObjectMapper();
		$mapper->delete($this);
	}
	
	public function addObserver(iObserver $view){
		$this->_observers[] = $view;
	}
	
	public function removeObserver(iObserver $view){
		foreach($this->_observers as &$observer){
			if($observer === $view){
				unset($observer);
			}
		}
	}
	
	public function notifyObservers($changes){
		foreach($this->_observers as $observer){
			$observer->update($this, $changes);
		}
	}
	
	public function __get($property){
		if(property_exists($this, '_' . $property)){
			return $this->{'_' . $property};
		}
	}
	
	public function __set($property, $value){
		if(property_exists($this, '_' . $property)){
			$this->{'_' . $property} = $value;
		}
	}
	
}