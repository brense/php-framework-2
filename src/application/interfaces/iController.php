<?php

namespace application\interfaces;

use application\interfaces\iObservable as iObservable;
use application\interfaces\iObserver as iObserver;

interface iController {
	
	public function setModel(iObservable $model);
	public function getModel();
	public function setView(iObserver $view);
	public function getView();

}