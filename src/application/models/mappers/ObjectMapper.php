<?php

namespace application\models\mappers;

use application\controllers\DbControllerFactory as DbControllerFactory;
use application\interfaces\iSaveable as iSaveable;
use application\models\Config as Config;

class ObjectMapper {
	
	protected $_db;
	protected $_table;
	protected $_class;
	
	public function __construct($class){
		$this->_class = ucfirst($class);
		$this->_table = @strtolower(@array_pop(@explode('\\', $class)));
		
		$this->_db = DbControllerFactory::getDbController(Config::instance()->db['type']);
		$this->_db->table = $this->_table;
	}
	
	public function create(iSaveable &$obj){
		$id = $this->_db->create($this->toArray($obj));
		if($id > 0){
			$obj->id = $id;
		}
	}
	
	public function read($args, $sort = null, $limit = null){
		if(is_array($args)){
			$results = $this->_db->read($args, $sort, $limit);
		}
		$objs = array();
		foreach($results as $result){
			$objs[] = $this->toObject($result);
		}
		return $objs;
	}
	
	public function update(iSaveable $obj){
		$this->_db->update($this->toArray($obj), array('id' => $obj->id));
	}
	
	public function delete(iSaveable $obj){
		$this->_db->delete(array('id' => $obj->id));
	}
	
	private function toArray(iSaveable $obj){
		$class = new \ReflectionClass($obj);
		$properties = array();
		foreach($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property){
			$properties[$property->getName()] = $property;
		}
		return $properties;
	}
	
	private function toObject(Array $array){
		$obj = new $this->_class();
		foreach($array as $property => $value){
			if(!is_numeric($property)){
				$obj->$property = $value;
			}
		}
		return $obj;
	}
	
}