<?php

namespace application\interfaces;

interface iObservable {
	
	public function notifyObservers($changes);
	
}