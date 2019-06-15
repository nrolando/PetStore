<?php
namespace PetStoreApi;
use PetStoreApi\Product\Controllers;

class restapi
{
    public function run($uri_params) {
        // route REST api request
        $apiMod = ucfirst($uri_params[1]);
        $apiAction = $uri_params[2];
        $controllerClassName = "PetStoreApi\\{$apiMod}\\Controllers\\{$apiMod}Controller";
        $actionMethod = "action_{$apiAction}";
        
        $controller = new $controllerClassName($uri_params);
        $controller->$actionMethod();
    }
}
