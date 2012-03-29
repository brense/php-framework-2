<?php

namespace application\views;

use application\views\AbstractView as AbstractView;
use application\models\Template as Template;
use application\models\Page as Page;

class PagesListView extends AbstractView {
	
	public function render(Array $options = array()){
		$pages = Page::find(array());
		$template = new Template('pages\list');
		$template->vars = new \StdClass();
		$template->setVar('pages', $pages);
		$template->render();
	}
	
}