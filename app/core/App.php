<?php
	//use app\core\Router;
	//	use app\core\Registry;
	/**
	* App
	*/
	class App
	{
		private $router;

		function __construct($config)
		{
			new Autoload($config['rootDir']);

			$this->router = new Router($config['basePath']);

			//Registry::getIntance()->config = $config;
		}

		public function run(){
			$this->router->run();
		}
	}
?>