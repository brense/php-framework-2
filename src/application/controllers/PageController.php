<?php

namespace application\controllers;

use application\controllers\AbstractController as AbstractController;
use application\models\Page as Page;

class PageController extends AbstractController {
	
	public function loadPage($uri){
		$parts = explode('/', $uri);
		for($n = count($parts)-1; $n >= 0; $n--){
			$uri = implode('/', $parts);
			$page = Page::find_by_uri($uri);
			if(isset($page[0]) && $page[0] instanceof Page){
				$this->_model = $page[0];
				break;
			}
			unset($parts[$n]);
		}
		return $this->_model;
	}
	
}