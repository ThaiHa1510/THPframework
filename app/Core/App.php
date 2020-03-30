<?php
	//use App\core\Router;
	//	use App\core\Registry;
	/**
	* App
	*/
	class App
	{
		private $router;

		function __construct($config)
		{
			//new Autoload($config['rootDir']);
			$this->bootstrap();
			$this->router = new Router($config['basePath']);

			//Registry::getIntance()->config = $config;
		}

		public function run(){
			$this->router->run();
		}
		private function make(){
			print_r("ok dang chy make");
		}
		private function bootstrap(){
			$this->autoLoadFile();
		}
		private function autoLoadFile(){
			foreach( $this->defaulFileLoad() as $file ){
				require_once( constant('ROOT').$file );
			}
		}

		private function defaulFileLoad(){
			return [
				'app/Core/Router.php',
				'app/routers.php'
			];
		}
	}
?>