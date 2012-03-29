<?php

namespace application\views;

use application\views\AbstractView as AbstractView;
use application\models\Template as Template;
use application\models\Content as Content;
use application\models\Page as Page;

class ContentListView extends AbstractView {
	
	public function render(Array $options = array()){
		$content = Content::find(array());
		$pages = array();
		foreach($content as $item){
			if($item->parent_type == 'page'){
				$results = Page::find_by_id($item->parent_id);
				$pages[$item->parent_id] = $results[0]->title;
			}
		}
		$template = new Template('content\list');
		$template->vars = new \StdClass();
		$template->setVar('content', $content);
		$template->setVar('pages', $pages);
		$template->render();
	}
	
}