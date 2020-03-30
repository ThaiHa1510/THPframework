<?php
namespace App\Core;
use App\Lib\Http\HttpKernel;
use App\Core\AppException;
use App\Lib\Pipeline\BasePipeline as Pipeline;
class Kernel implements HttpKernel
{
    /**
     * Routing object 
     */
    private $route;
    /**
     * Contructor
     */
    public function __construct($router)
    {
        $this->router=$router;
        $this->syncMiddlewareToRouter();
    }
    
    /**
     * @var the app middleware stack
     */
    private $middleware = [];
    /**
     * @var group middleware 
     */
    private $groupMiddleware=[];
    /**
     * @var middleware route
     */
    private $routeMiddleware=[];
    private function syncMiddlewareToRouter(){
        $this->router->middleware=$this->$middleware;
        foreach($groupMiddleware as $key => $middleMethod){
            $this->router->groupMiddleware($key,$middleMethod);
        }
        foreach($routeMiddleware as $key=>$method){
            $this->router->routeMiddleware($key,$method);
        }
    }
    public function handle()
    {
        try{
            $this->request->overideHttpMethodsParams();
            $reponse=$this->sendRequestThroughRouter($request);
        }
        catch (AppException $e){
            $response =new AppException();
        }
        return $response;
    }
    protected function sendRequestThroughRouter($request){
        return (new Pipeline())
        ->send($request)
        ->through($this->skippMiddleware ? []:$this->middleware)
        ->then($this->dispathToRouter($request));
    }


}