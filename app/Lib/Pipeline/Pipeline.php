<?php
namespace App\Lib\Pipeline;
use Exception;
use RuntimeException;
class BasePipeline {
    //protected $container;
    protected $passable;
    protected $method="handle";
    protected $pipes;
    /**
     * Contructor function 
     * @param container 
     
    
    public function __contruct(Application $app){
        $this->container=$app;
    
    }
    */
    /**
     * Function set $passable 
     * @param $passable 
     * return this
     */

    public function send($passable){
        $this->passable=$passable;
        return $this;
    }

    public function through($pipes){
        $this->pipes=$pipes;
        return $this;
    }
    public function then(Closure $call ){
        $pipeline=array_reduce(array_reverse($this->pipes),$this->carry(), $this->prepareCall($call));
        return $pipeline($this->passable);
    }
    protected function carry(){
        return function ($stack , $pipe){
            return function ($passable) use ($stack, $pipe){
                try{
                    
                    if(is_callable($pipe)){
                        return $pipe($passable);
                    }
                    else{
                       
                        $paramater=[$passable,$stack];  
                        $carry = method_exists($pipe, $this->method)
                        ? (new $pipe())->{$this->method}($passable,$stack)
                        : (new $pipe())($passable,$stack);
                    }
                    return $this->hanldCarry($carry);
                }
                catch (Exception $e){
                    return $this->handleExpresstion($passable,$e);
                }
            };
        };
    }
    /** 
    protected function container(){
        if(is_null($this->container)){
            throw new RuntimeException("Container not pass to Pipeline") ;
        }
        else{
            return $this->container;
        }
    }
    */
    protected function hanldCarry($carry){
        return $carry;
    }
    protected function pipes(){
        return $this->pipes;
    }
    protected function prepareCall($call){
        return $call();
    }
    public function via($method){
        $this->method=$method;
        
        return $this;
    }

}