<?php
namespace PetStoreApi\Core\Controllers;

abstract class AbstractController
{
    protected $http_verb;
    protected $request_params;
    
    public function __construct($reqParams) {
        $this->request_params = $reqParams;
    }
    
    protected function sendResponse($content) {
        // Set response headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        // Set response data to be sent
        $response = array(
            "content" => $content,
            "_links" => array(
                "self" => array(
                    "href" => $_SERVER["REQUEST_URI"],
                ),
            )
        );

        // Set response code - 200 OK
        http_response_code(200);

        // echo json response
        echo json_encode($response);
    }
}

