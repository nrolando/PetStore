<?php
/* author: Nick Rolando
 * date: 2019-06-12
 * desc: Index file our RESTful API. Should return hypertext to help clients navigate API
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$response = array(
    "content" => "Hello, World!",
    "_links" => array(
        "self" => array(
            "href" => $_SERVER["REQUEST_URI"],
        ),
    )
);

// set response code - 200 OK
http_response_code(200);

echo json_encode($response);
