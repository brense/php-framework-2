<?php

namespace application\controllers;

abstract class CommandFactory {
	
	public static function getCommand($name){
		$class = 'application\commands\\' . $name . 'Command';
		if(class_exists($class)){
			return new $class();
		} else {
			throw new \Exception($name . 'Command not found');
		}
	}
	
}