<?php

namespace application\models;

use application\interfaces\iSaveable as iSaveable;
use application\interfaces\iObservable as iObservable;
use application\models\Content as Content;
use application\models\Template as Template;

class Page extends Content implements iSaveable, iObservable {
	
	protected $_type = 'page';
	protected $_template;
	protected $_uri;
	protected $_title;
	protected $_theme;

	public function __construct(){
		
	}
	
	public function display(){
		echo parent::display();
	}
	
	protected function parse($html){
		
		$scripts = array();
		if($handle = opendir(str_replace('custom\\', '', Config::instance()->appPath) . Config::instance()->scriptPath)){
			while(($entry = readdir($handle)) !== false){
				if($entry != '.' && $entry != '..'){
					$scripts[] = Config::instance()->scriptPath . $entry;
				}
			}
			closedir($handle);
		}
		
		$content = '';
		ob_start();
		$template = new Template($this->_template);
		$template->vars = new \StdClass();
		$template->setVar('page_title', $this->_title . ' \ ' . Config::instance()->title);
		$template->setVar('main_theme', Config::instance()->themesPath . Config::instance()->theme . '.css');
		if(isset($this->_theme) && strlen($this->_theme) > 0){
			$template->setVar('page_theme', Config::instance()->themesPath . $this->_theme . '.css');
		}
		$template->setVar('root', Config::instance()->rootUrl);
		$template->setVar('scripts', $scripts);
		$template->setVar('content', $html);
		$template->render();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

}