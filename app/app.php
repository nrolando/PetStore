<?php
namespace PetStoreApp;
use PetStoreApi;

class app
{
    public function run() {
        // Build an array of query string parameters
        $qs = $_SERVER["QUERY_STRING"];
        
        // explode query string by '/' from start of string to '&' char. '&' params will be parsed next.
        $uri_params = explode('/', rtrim(
            substr($qs, 3, ((strpos($qs, '&') === false) ? 512 : strpos($qs, '&') - 3))
        , '/'));
        
        // check if there are query string params following '?', e.g. ?k1=v1&k2=v2
        if(strpos($qs, '&') !== false) {
            $extra_qs_params = explode('&', substr($qs, (strpos($qs, '&') + 1)));
        
            foreach($extra_qs_params as $p) {
                $kp = explode('=', $p);
                $uri_params[$kp[0]] = $kp[1];
            }
        }
        
        // module 'rest' routes to the REST api
        if($uri_params[0] === "rest") {
            $api = new \PetStoreApi\restapi();
            $api->run($uri_params);
        }
    }
}