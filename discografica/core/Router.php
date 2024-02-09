<?php

class Router {

    private $routes = [];

    function setRoutes(Array $routes) {
        $this->routes = $routes;
    }

    function getFilename(string $url) {
        error_log("Buscando ruta para url $url");
        foreach($this->routes as $route => $file) {
            if(strpos($url, $route) != false){
                error_log("Ruta encontrada: $file");
                return $file;
            }
        }
        error_log("No se ha encontrado la ruta");

    }
}
