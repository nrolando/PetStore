<?php
namespace PetStoreApi\Product\Controllers;
use PetStoreApi\Core\Controllers;
use PetStoreApp\Product\Model;

class ProductController extends \PetStoreApi\Core\Controllers\AbstractController
{
    public function __construct($reqParams) {
        parent::__construct($reqParams);
    }
    
    public function action_get() {
        // Check for product id to get
        if(isset($this->request_params[3])) {
            $prodId = $this->request_params[3];
            
            // get any additional parameters
            
            // get products
            $p = new \PetStoreApp\Product\Model\ModelProduct();
            $p->load($prodId);
            
            // set response content
            $content = "product id name is " . $p->getname();
        } else {
            $content = "No product id to get";
        }
        $this->sendResponse($content);
    }
}
