<?php

require_once 'Rse.php';

class Apl extends Rse
{
	protected $controller = 'home';
	
	protected $method = 'index';
	
	protected $params;
	
	public function __construct()
	{
		$this->cors();

		$url = $this->parseUrl();

		//$this->validateUrl();
	 
		if(!file_exists('../api/controllers/' . $url[0] . '.php')){
			echo 'No existe el nombre del controller';
            return;
		}
		$this->controller = $url[0];
		unset($url[0]);		 
		require_once '../api/controllers/' . $this->controller . '.php';

		$this->controller = new $this->controller;
		 
		if(isset($url[1])){
		    if(method_exists($this->controller, $url[1])){			
				$this->method = $url[1];
				unset($url[1]);			
			}
			else{
				echo 'No existe el metodo';
				return;				
			}
		}
		else{
		    echo 'Se debe indicar un método valido';
            return;
		}
		 
		if($url){
		    $this->params = (array)array_values($url);		 
		}
		else{		 
			$this->params = array();				 
		}

		try{
			call_user_func_array(array($this->controller, $this->method), $this->params);			 		 			
	    } catch(SesionException $e) {
	        $this->controller->success = false;
	        $this->controller->msg = $this->msgErr("ses", $e);
	    } catch(SeguridadException $e) {
	        $this->controller->success = false;
	        $this->controller->msg = $this->msgErr("sec", $e);
	    } catch(BaseDatosException $e) {
	        $this->controller->success = false;        
	        $this->controller->msg = $this->msgErr("dba", $e);
	    } catch(Exception $e) {
	        $this->controller->success = false;
	        $this->controller->msg = $this->msgErr("ser", $e);
	    } finally {
	      $this->sendJason($this->controller->success,
		                   $this->controller->msg,
						   $this->controller->list, 
						   $this->controller->total,
						   $this->controller->trtbl);
		}		

	}
	
	
	private function parseUrl()
	{
		if(isset($_GET['url']))
		{
			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));		
		}
	}

	private function validateUrl(){
		
		foreach($_GET as $clave=>$valor){
			$pi = 1;

			if($clave == 'start' && $valor == 0){
				$pi = 0;
			}

			$_GET[$clave] = $_GET[$clave] ? $_GET[$clave] : null;

			if($_GET[$clave]=='null'){
				$_GET[$clave] = null;
			}
			if($_GET[$clave] == "''"){
				$_GET[$clave] = null;
			}
            if($_GET[$clave] == ""){
				$_GET[$clave] = null;
			}

			if($pi == 0){
				$_GET[$clave] = 0;
			}			
		}
	}

	private function cors(){
		
	    //OPC1> Estos se activan si las vistas no se refrescan en servidor productivo
        // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		// header("Cache-Control: post-check=0, pre-check=0", false);
		// header("Pragma: no-cache");
		// header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		// Indicar que la respuesta es un JSON en tiempo real
		// header("Content-Type: application/json; charset=utf-8");
		//<OPC1

		if(isset($_SERVER['HTTP_ORIGIN'])) {			
		    //if($_SERVER['HTTP_ORIGIN']=="http://localhost:4200"){ //La condición se puede eliminar con el else				
				header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
				header('Access-Control-Allow-Credentials: true');
				header('Access-Control-Max-Age: 86400');
			// }
			// else{
			// 	exit(0);
			// }
		}

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS' ) {
    
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))

				header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
			
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
		
			exit(0);
		}

	}

}
?>