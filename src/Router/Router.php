<?php

namespace Router;
use Symfony\Component\Yaml\Exception\RuntimeException;

class Router implements \Countable{
	private $routes;

	public function count(){
		return count($this->routes);
	}

	public function addRoute(array $array){
		foreach($array as $k=>$v){
			if(!empty($this->routes)){
				throw new \RuntimeException(sprintf('Cannot override route "%s".',$k));
			}
		}
		$this->routes[$k]=$v;
	}

	public function getRoute($url){
		foreach($this->routes as $v){
			$preg_value="/^".$v['pattern']."$/";
			$check=preg_match($preg_value,$url,$matches);
			if(!$check){
				continue;
			}
			$arrayConnect=explode(':',$v['connect']);
			$array= [
				'controller'=>$arrayConnect[0],
				'action'=>$arrayConnect[1]
			];
			if(isset($v['params'])){
					$array['params']=$this->getParams($v['params'],$matches);
			}
			return $array;

		}
		throw new \RuntimeException('Pas de routes');
	}

	public function getParams($sentParams,$matches){
		$params=[];
		$arrayParams=explode(',',$sentParams);
		foreach($arrayParams as $v){
			$params[$v]=$matches[$v];
		}
		return $params;
	}
}