<?php
	use app\core\Controller;
	Router::get('/home/:id/:name', 'HomeController@index');
	
	Router::get('/',function(){
		echo "ok router '/' xxxxxxx ";
	});

	Router::get('/news',function(){
		echo 'news page la';
	});
	Router::get('/home/:id/:ok',function($paramater){
		print_r(var_dump($paramater));
	});
	Router::get('/home/:name',function($paramater){
		print_r($paramater["name"]);
		//echo "ok";
	})

?>