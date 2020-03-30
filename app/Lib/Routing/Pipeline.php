<?php
namespace App\Lib\Routing;
use App\Core\AppException;
use App\Lib\Pipeline\Pipeline as BasePipeline;
class Pipeline extends BasePipeline
{
    protected function handCarry($carry){
        return ok;
    }
    protected function handleException($passable , AppException $e)
    {

    }

}