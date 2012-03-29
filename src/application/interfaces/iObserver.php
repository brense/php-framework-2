<?php

namespace application\interfaces;

use application\interfaces\iObservable as iObservable;

interface iObserver {
	
	public function update(iObservable $observable, $changes);
	
}