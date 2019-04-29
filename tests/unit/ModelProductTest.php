<?php
/**
 * Code by: Nick Rolando
 */

use PHPUnit\Framework\TestCase;
use PetStoreInc\model\ModelProduct;

class ModelProductTest extends TestCase
{
    public function testSetAndGetProductData() {
        $mp = new ModelProduct();
        
        $name = "Cheese Burgers";
        $petType = "";
        $itemType = "";
        $color = "";
        $age = 4;
        $lifespan = 6;
        $price = 75;
        
        $mp->setpet_type('name', $name);
        $mp->setData('name', $name);
        $this->assertTrue($mp->getData('name') === $name);
    }
    
    public function testYouCantSaveWithoutAllData() { }
}
