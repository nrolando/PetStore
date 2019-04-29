<?php
/**
 * Code by: Nick Rolando
 */

use PHPUnit\Framework\TestCase;
use PetStoreInc\model\ModelAbstract;

class ModelAbstractTest extends TestCase
{
    /**
     * Test set and get data using available techniques in the abstract class.
     */
    public function testSetAndGetData() {
        $ma = new ModelAbstract();
        $name = "Nicholas";
        $age = 32;
        $ht = 6.2;
        
        $ma->setData('name', $name);
        $ma->setage($age);
        $ma->height = $ht;
        
        $this->assertTrue($ma->getData('name') === $name);
        $this->assertTrue($ma->getage() === $age);
        $this->assertTrue($ma->height === $ht);
    }
}
