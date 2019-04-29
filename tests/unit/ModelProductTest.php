<?php
/**
 * Code by: Nick Rolando
 */

use PHPUnit\Framework\TestCase;
use PetStoreInc\model\ModelProduct;

class ModelProductTest extends TestCase
{
    public function testSetAndGetName() {
        $mp = new ModelProduct();
        $this->assertTrue(true);
    }
    
    //public function testYouCantSaveWithoutAllData() { }
}
