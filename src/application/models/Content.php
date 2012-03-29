<?php

namespace application\models;

use application\interfaces\iSaveable as iSaveable;
use application\interfaces\iObservable as iObservable;
use application\models\AbstractModel as AbstractModel;

class Content extends AbstractModel implements iSaveable, iObservable {
	
	protected $_text;
	protected $_parent_type;
	protected $_parent_id;
	protected $_type;
	protected $_content;
	protected $_range;
	protected $_children = array();

	public function __construct(){
		
	}
	
	public function setChildren($children){
		if($this->_type == 'page' || $this->_type == 'mvc' || $this->_type == 'template'){
			$this->_children = $children;
		}
	}
	
	public function getChildren(){
		return $this->_children;
	}
	
	public function addChild(Content $child){
		if($this->_type == 'page' || $this->_type == 'mvc' || $this->_type == 'template'){
			$this->_children[] = $child;
		}
	}
	
	public function removeChild(Content $child){
		$key = array_search($child, $this->_children, true);
		if($key !== false){
			unset($this->_children[$key]);
		}
	}
	
	public function display(){
		$html = '';
		foreach($this->_children as $child){
			if(method_exists($child, 'display')){
				$html .= $child->display();
			}
		}
		return $this->parse($html);
	}
	
	protected function parse($html){
		$this->_content = json_decode($this->_content);
		ob_start();
		switch($this->_type){
			case 'mvc':
				$model = new $this->_content->model();
				$controller = new $this->_content->controller($model);
				$view = new $this->_content->view($model, $controller);
				$view->render(array('content' => $html));
				break;
			case 'template':
				$template = new Template($this->_content->template);
				$template->vars = $this->_content->template_vars;
				$template->setVar('content', $html);
				$template->render();
				break;
			case 'gadget':
				$gadget = new Gadget($this->_content->gadget_url);
				$gadget->vars = $this->_content->gadget_vars;
				$gadget->render();
				break;
			case 'widget':
				$widget = new Widget($this->_content->widget);
				$widget->vars = $this->_content->widget_vars;
				$widget->render();
				break;
			case 'text':
				echo $this->_content->text;
				break;
		}
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

}