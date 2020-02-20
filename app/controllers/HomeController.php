<?php
namespace app\controllers;
use app\core\Controller;
use Twig\TwigFilter;
use Twig\TwigFunction;
use app\core\Registry;
/**
	* HomeController
	*/
	class HomeController extends Controller
	{
		
		function __construct()
		{
			parent::__construct();
		}

		public function index($id,$name){
			echo $id;
			echo '<br>'.$name;

		}
		public function views(){
		$products = [
			[
				'name'          => 'Notebook',
				'description'   => 'Core i7',
				'value'         =>  800.00,
				'date_register' => '2017-06-22',
			],
			[
				'name'          => 'Mouse',
				'description'   => 'Razer',
				'value'         =>  125.00,
				'date_register' => '2017-10-25',
			],
			[
				'name'          => 'Keyboard',
				'description'   => 'Mechanical Keyboard',
				'value'         =>  250.00,
				'date_register' => '2017-06-23',
			],
		];

		$this->render("index.html", ['products' => $products]);
		//echo $view;
		

		}
	}
?>