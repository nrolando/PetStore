<?php
/**
 * Code by: Nick Rolando
 */

use PHPUnit\Framework\TestCase;
use PetStoreInc\db\PdoDbConn;

class PdoDbConnTest extends TestCase
{
    /**
     * Test that the PdoDbConn is a singleton class, and that multiple instances cannot be created
     */
    public function testIsSingleton() {
        $dbConn1 = PdoDbConn::getInstance();
        $dbConn2 = PdoDbConn::getInstance();
        
        // Assert the two variables above are referencing the same object
        $this->assertEquals(true, $this->areInstancesTheSame($dbConn1, $dbConn2));
    }
    
    public function testCanSelectFromDb() {
        $dbConn1 = PdoDbConn::getInstance();
        $r = $dbConn1->doQuery("SELECT * FROM `pet_store`.`products` LIMIT 0, 1");
        $rid = intval($r["id"]);
        $this->assertTrue($rid > 0);
    }
    
    private function areInstancesTheSame(&$obj1, &$obj2) {
        return $obj1 === $obj2;
    }
}
