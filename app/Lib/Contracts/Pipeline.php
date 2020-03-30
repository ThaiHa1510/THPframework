<?php
namespace App\Lib\Contracts;

interface Pipeline {
    
    
    function send();
    function through();
    function then();
}