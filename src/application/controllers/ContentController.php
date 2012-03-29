<?php

namespace application\controllers;

use application\controllers\AbstractController as AbstractController;
use application\controllers\DbControllerFactory as DbControllerFactory;
use application\models\Content as Content;
use application\models\Page as Page;
use application\models\Config as Config;

class ContentController extends AbstractController {
	
	public function loadContent(Content &$parent){
		
		$type = @strtolower(@array_pop(@explode('\\', @get_class($parent))));
		if($parent instanceof Page){
			$db = DbControllerFactory::getDbController(Config::instance()->db['type']);
			$results = $db->query('SELECT * FROM content WHERE parent_type = "' . $type . '" AND parent_id = "' . $parent->id . '" OR `range` = "' . @array_shift(@explode('/', $parent->uri)) . '"', array(), 'fetchAll');
			$content = array();
			foreach($results as $result){
				$obj = new Content();
				foreach($result as $property => $value){
					if(!is_numeric($property)){
						$obj->$property = $value;
					}
				}
				$content[] = $obj;
			}
		} else {
			$content = Content::find(array('parent_type' => $type, 'parent_id' => $parent->id));
		}
		
		$parent->setChildren($content);
		
		if(is_array($content)){
			foreach($content as &$child){
				$this->loadContent($child);
			}
		} else {
			$this->loadContent($content);
		}
	}
	
}