<?php

namespace application\controllers;

use application\models\Config as Config;

class PdoDbController {
	
	private $_table;
	private $_handle;
	private $_query;
	
	public function __construct($host, $name, $user, $pswd){
		$this->_handle = new \PDO("mysql:host=" . $host . ";dbname=" . $name, $user, $pswd);
	}
	
	public function __destruct(){
		unset($this->_handle);
	}
	
	public function query($query, Array $params = array(), $return = null){
		$this->_query = $this->execute($query, $params);
		if(isset($return)){
			switch($return){
				case 'fetchAll': return $this->fetchAll(); break;
				case 'lastInsertId': return $this->getLastInsertId(); break;
			}
		}
	}
	
	private function execute($query, Array $params = array()){
		$this->_query = $this->_handle->prepare($query);
		if(is_array($params)){
			foreach($params as $key => $value){
				$this->_query->bindValue($key, $value);
			}
		}
		$this->_query->execute();
		return $this->_query;
	}
	
	public function create(Array $values){
		$cols = array();
		$vals = array();
		$params = array();
		foreach($values as $key => $value){
			$cols[] = $key;
			$vals[] = ':' . $key;
			$params[':' . $key] = $value;
		}
		$cols = implode(', ', $cols);
		$vals = implode(', ', $vals);
		$query = "INSERT INTO " . $this->_table . " (" . $cols . ") VALUES(" . $vals . ")";
		$this->_query = $this->execute($query, $params);
		return $this->getLastInsertId();
	}
	
	public function read(Array $crits = array(), $sort = null, $limit = null){
		if(isset($sort)) $sort = ' ' . $sort; // TODO improve sort
		if(isset($limit)) $limit = ' ' . $limit; // TODO improve limit
		$criteria = array();
		$params = array();
		if(isset($crits)){
			foreach($crits as $field => $value){
				$criteria[] = '`' . $field . '` = :crit' . $field;
				$params[':crit' . $field] = $value;
			}
			$criteria = implode(' AND ', $criteria);
		}
		if(strlen($criteria) > 0){
			$criteria = ' WHERE ' . $criteria;
		}
		$query = "SELECT * FROM " . $this->_table . $criteria . $sort . $limit;
		$this->_query = $this->execute($query, $params);
		return $this->fetchAll();
	}
	
	public function update(Array $crits, Array $values){
		$cols = array();
		$params = array();
		foreach($values as $key => $value){
			$cols[] = $key . '=:' . $key;
			$params[':' . $key] = $value;
		}
		$cols = implode(', ', $cols);
		$criteria = '';
		foreach($crits as $field => $value){
			$criteria .= $field . ' = :crit' . $field;
			$params[':crit' . $field] = $value;
		}
		if(isset($criteria)){
			$criteria = ' WHERE ' . $criteria;
		}
		$query = "UPDATE " . $this->_table . " SET " . $cols . $criteria;
		$this->_query = $this->execute($query, $params);
	}
	
	public function delete(Array $crits){
		foreach($crits as $field => $value){
			$criteria .= $field . ' = :crit' . $field;
			$params[':crit' . $field] = $value;
		}
		if(isset($criteria)){
			$criteria = ' WHERE ' . $criteria;
		}
		$query = "DELETE FROM " . $this->_table . ' ' . $criteria;
		$this->_query = $this->execute($query, $params);
	}
	
	private function getLastInsertId(){
		return $this->_handle->lastInsertId();
	}
	
	private function fetchAll(){
		return $this->_query->fetchAll();
	}
	
	public function __set($property, $value){
		switch($property){
			case 'table':
				$this->_table = Config::instance()->db['prfx'] . $value;
				break;
		}
	}
	
}