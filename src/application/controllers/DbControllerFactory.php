<?php

namespace application\controllers;

use application\controllers\PdoDbController as PdoDbController;
use application\models\Config as Config;

abstract class DbControllerFactory {
	
	public static function getDbController($name){
		switch($name){
			case 'PDO':
			default:
				return new PdoDbController(Config::instance()->db['host'], Config::instance()->db['name'], Config::instance()->db['user'], Config::instance()->db['pswd']);
				break;
		}
	}
	
}