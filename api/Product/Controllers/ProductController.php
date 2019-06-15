<?php
namespace PetStoreApi\Product\Controllers;
use PetStoreApi\Core\Controllers\AbstractController;
use PetStoreApp\Product\Model\ModelProduct;
use PetStoreApp\Product\Model\Res\CollectionProduct;

class ProductController extends AbstractController
{
    public function __construct($reqParams) {
        parent::__construct($reqParams);
    }
    
    public function action_get() {
        $content = array();
        $i = 0;
        
        // Check for product id to get
        if(isset($this->request_params[3])) {
            $prodId = $this->request_params[3];
            
            // to-do: get any additional parameters
            
            if($prodId === "all") {
                // Get all products
                $products = new CollectionProduct();
                $products->loadCollection();
                foreach($products->getCollection() as $prod) {
                    $content['products'][$i]['id'] = $prod->getid();
                    $content['products'][$i]['name'] = $prod->getname();
                    $content['products'][$i]['pet_type'] = $prod->getpet_type();
                    $content['products'][$i]['item_type'] = $prod->getitem_type();
                    $content['products'][$i]['color'] = $prod->getcolor();
                    $content['products'][$i]['lifespan'] = $prod->getlifespan();
                    $content['products'][$i]['age'] = $prod->getage();
                    $content['products'][$i]['price'] = $prod->getCalculatedPrice();
                    $i++;
                }
            } else {
                // get products
                $prod = new ModelProduct();
                $prod->load($prodId);
                $content['product']['id'] = $prod->getid();
                $content['product']['name'] = $prod->getname();
                $content['product']['pet_type'] = $prod->getpet_type();
                $content['product']['item_type'] = $prod->getitem_type();
                $content['product']['color'] = $prod->getcolor();
                $content['product']['lifespan'] = $prod->getlifespan();
                $content['product']['age'] = $prod->getage();
                $content['product']['price'] = $prod->getCalculatedPrice();
            }
        } else {
            $content = "No product id to get";
        }
        $this->sendResponse($content);
    }
}
