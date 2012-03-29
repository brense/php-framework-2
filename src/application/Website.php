<?php

namespace application;

use application\models\Config as Config;
use application\models\Request as Request;
use application\models\Page as Page;
use application\models\Content as Content;
use application\controllers\PageController as PageController;
use application\controllers\ContentController as ContentController;
use application\controllers\CommandFactory as CommandFactory;

class Website {
	
	private $_sources = array();
	private static $_loaded = array();
	public static $instance;
	
	protected function __construct(){
		// set the class sources
		$this->_sources['APP_PATH'] = str_replace('/', '\\', str_replace('index.php', 'custom\\', $_SERVER['SCRIPT_FILENAME']));
		$this->_sources['APA_PATH'] = str_replace('application\Website.php', '', __FILE__);
		
		// set the autoloader
		spl_autoload_register(array($this, 'autoload'));
		self::$_loaded[] = 'BasicApplication';
		
		// set the exception and error handler
		set_exception_handler(array($this, 'handleExceptions'));
		set_error_handler(array($this, 'handleErrors'));
		
		// start a session
		session_start();
		
		// load the config file
		Config::instance()->load($this->_sources['APP_PATH'] . 'config.php');
		Config::instance()->apaPath = $this->_sources['APA_PATH'];
		Config::instance()->appPath = $this->_sources['APP_PATH'];
		Config::instance()->rootUrl = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
		
		// set the timezone
		date_default_timezone_set(Config::instance()->timezone);
	}
	
	public static function instance(){
		if(empty(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function autoload($class){
		if(!in_array($class, self::$_loaded)){
			$path = str_replace('\\', '/', $class);
			$path = str_replace('_', '/', $class);
			$found = false;
			foreach($this->_sources as $source){
				if(file_exists($source . $path . '.php')){
					include($source . $path . '.php');
					spl_autoload($class);
					self::$_loaded[] = $class;
					$found = true;
					break;
				}
			}
			if(!$found){
				throw new \Exception('class ' . $class . ' not found');
			}
		}
	}
	
	public function handleRequest(Request $request){
		if(($request->method == 'GET' && substr($request->uri, 0, 6) != 'views/' && substr($request->uri, 0, 4) != 'api/') || ($request->method == 'GET' && substr($request->uri, 0, 4) == 'api/' && strlen($request->uri) == 4)){
			$page = new Page();
			$pageController = new PageController($page);
			$contentController = new ContentController(new Content());
			$page = $pageController->loadPage($request->uri);
			$contentController->loadContent($page);
			$page->display();
		} else {
			$cmd = '';
			if(substr($request->uri, 0, 4) == 'api/'){
				if($request->method == 'GET'){
					$cmd = 'api\get\\' . substr($request->uri, 4);
				} else if($request->method == 'POST'){
					$cmd = 'api\post\\' . substr($request->uri, 4);
				}
			} else if($request->method == 'POST'){
				$cmd = 'post\\' . $request->uri;
			}
			
			$command = CommandFactory::getCommand($cmd, $request);
			$command->execute($request);
		}
	}
	
	public static function handleExceptions(\Exception $exception) {
		// TODO: log exceptions
		if(Config::instance()->debug){
			echo '<pre>';
			print_r($exception);
			echo '</pre>';
		} else {
			echo 'something went wrong';
		}
		exit;
	}
	
	public static function handleErrors($errno, $errstr, $error_file = null, $error_line = null, Array $error_context = null) {
		// TODO: log errors
		if(Config::instance()->debug){
			$error = array(
				'no' => $errno,
				'error' => $errstr,
				'file' => $error_file,
				'line' => $error_line,
				'context' => $error_context
			);
			echo '<pre>';
			print_r($error);
			echo '</pre>';
		}
	}
	
}