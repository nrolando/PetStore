<?php
/**
 * Code by: Nick Rolando
 */

use PHPUnit\Framework\TestCase;
use PetStoreInc\model\ModelProduct;

class ModelProductTest extends TestCase
{
    public function testSetAndGetAllProductData() {
        $mp = new ModelProduct();
        
        $name = "Kermit";
        $petType = "Frog";
        $itemType = "Pet";
        $color = "green";
        $age = 4;
        $lifespan = 6;
        $price = 75.99;
        
        $mp->setpet_type($petType);
        $mp->setData('item_type', $itemType);
        $mp->name = $name;
        $mp->setData(array(
            "color" => $color,
            "lifespan" => $lifespan,
            "age" => $age,
            "price" => $price
        ));
        
        $this->assertTrue($mp->getpet_type() === $petType);
        $this->assertTrue($mp->getData('item_type') === $itemType);
        $this->assertTrue($mp->name === $name);
        $this->assertTrue($mp->getData('color') === $color);
        $this->assertTrue($mp->getData('lifespan') === $lifespan);
        $this->assertTrue($mp->getData('age') === $age);
        $this->assertTrue($mp->getData('price') === $price);
        
        $this->assertTrue($mp->isAllDataSet() === true);
    }
    
    /**
     * Test price calculations with different age/lifespan/price values.
     */
    public function testPriceCalculations() {
        $mp = new ModelProduct();
        
        $name = "Skeletor";
        $petType = "Antagonist";
        $itemType = "Pet";
        $color = "black";
        
        // Set all data except price
        $mp->setData(array(
            "pet_type" => $petType,
            "item_type" => $itemType,
            "name" => $name,
            "color" => $color
        ));
        
        // Set lifespan & price and assert calculated price
        $mp->setData('lifespan', 6);
        $mp->setData('age', 4);
        $mp->setData('price', 75);
        // Should I hardcode expected values for testing??? We will say yes for now...
        //$this->assertTrue($mp->getCalculatedPrice() === (bcmul($mp->getprice(), $mp->getOldProductDiscount(), 2)));
        $this->assertTrue($mp->getCalculatedPrice() === '37.50');
        
        // Set lifespan & price and assert calculated price
        $mp->setData('lifespan', 9);
        $mp->setData('age', 4);
        $mp->setData('price', 328);
        // Should I hardcode expected values for testing??? We will say yes for now...
        //$this->assertTrue($mp->getCalculatedPrice() === (bcmul($mp->getprice(), $mp->getOldProductDiscount(), 2)));
        $this->assertTrue($mp->getCalculatedPrice() === '328.00');
        
        // Set lifespan & price and assert calculated price
        $mp->setData('lifespan', 9.4);
        $mp->setData('age', 4.7);
        $mp->setData('price', 83.23);
        // Should I hardcode expected values for testing??? We will say yes for now...
        //$this->assertTrue($mp->getCalculatedPrice() === (bcmul($mp->getprice(), $mp->getOldProductDiscount(), 2)));
        $this->assertTrue($mp->getCalculatedPrice() === '41.61');
    }
}
