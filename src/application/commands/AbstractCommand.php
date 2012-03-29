<?php

namespace application\commands;

use application\models\Request as Request;

abstract class AbstractCommand {
	
	public function __construct(){}
	
	abstract public function execute(Request $request);
	
}