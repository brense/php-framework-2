<?php

namespace application\commands;

use application\commands\AbstractCommand as AbstractCommand;
use application\models\Request as Request;

class HomeCommand extends AbstractCommand {
	
	public function __construct(){
		
	}
	
	public function execute(Request $request){
		echo 'Welcome to APA framework';
	}
	
}