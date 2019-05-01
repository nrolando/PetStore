<?php
/**
 * Code by: Nick Rolando
 */
namespace PetStoreInc\model;
use PetStoreInc\Helper;
use PetStoreInc\db\PdoDbConn;

class ModelProduct extends ModelAbstract
{
    private const DB_TBL_NAME = "`";
    private const OLD_PRODUCT_DISCOUNT = .5;
    
    /**
     * Add a product to the db.
     * @throws \Exception
     */
    public function save() {
        if(!$this->isAllDataSet()) {
            throw new \Exception("Cannot save product due to insufficient data.");
        }
        
        $dbConn = PdoDbConn::getInstance();
        
        if(isset($this->id)) {
            //Update product
            $query = "UPDATE " . $this->getDbNameTbl() . " SET `name` = :name, pet_type = :petType, item_type = :itemType, color = :color, "
                . "lifespan = :lifespan, age = :age, price = :price WHERE `id` = " . $this->id;
            $dbConn->doParaManipQry($query, array(
                'name' => $this->name,
                'petType' => $this->pet_type,
                'itemType' => $this->item_type,
                'color' => $this->color,
                'lifespan' => $this->lifespan,
                'age' => $this->age,
                'price' => $this->price
            ));
        } else {
            //Insert new product and set id
            $query = "INSERT INTO " . $this->getDbNameTbl() . " (`name`, pet_type, item_type, color, lifespan, `age`, `price`) "
                . "VALUES(:name, :petType, :itemType, :color, :lifespan, :age, :price)";
            $dbConn->doParaManipQry($query, array(
                'name' => $this->name,
                'petType' => $this->pet_type,
                'itemType' => $this->item_type,
                'color' => $this->color,
                'lifespan' => $this->lifespan,
                'age' => $this->age,
                'price' => $this->price
            ));
            $query = "SELECT LAST_INSERT_ID() as last_id;";
            $r = $dbConn->doQuery($query);
            $this->id = $r['last_id'];
        }
        
        return $this;
    }
    
    /**
     * Select product by id parameter and load object.
     * @param id The ID of product to load.
     */
    public function load($id) {
        $dbConn = PdoDbConn::getInstance();
        
        $query = "SELECT `name`, pet_type, item_type, color, lifespan, age, `price` FROM "
            . $this->getDbNameTbl() . " WHERE `id` = :id";
        
        $ds = $dbConn->doParaSelectQry($query, array('id' => $id));
        
        if(!is_array($ds) || empty($ds)) {
            throw new \Exception("Product id " . $id . " not found.");
        }
        
        // Get (only) row
        $r = $ds[0];
        
        $this->id = $id;
        $this->name = $r['name'];
        $this->pet_type = $r['pet_type'];
        $this->item_type = $r['item_type'];
        $this->color = $r['color'];
        $this->lifespan = $r['lifespan'];
        $this->age = $r['age'];
        $this->price = $r['price'];
        
        return $this;
    }
    
    /**
     * Deletes product with set id, and unset product data.
     */
    public function delete() {
        if(isset($this->id)) {
            $dbConn = PdoDbConn::getInstance();
            $query = "DELETE FROM " . $this->getDbNameTbl() . " WHERE `id` = :id";
            $dbConn->doParaManipQry($query, array('id' => $this->id));
        }
        
        $this->setData();
    }
    
    /**
     * Deletes product from the database.
     * @param id product id to delete
     */
    public static function deleteId($id) {
        if(is_string($id) || is_int($id)) {
            $dbConn = PdoDbConn::getInstance();
            $query = "DELETE FROM `" . Helper::$dbName . "`.`" . Helper::$tblName_product . "` WHERE `id` = :id";
            $dbConn->doParaManipQry($query, array('id' => $id));
        }
    }
    
    private function getDbNameTbl() {
        return "`" . Helper::$dbName . "`.`" . Helper::$tblName_product . "`";
    }
    
    public function getCalculatedPrice() {
        if(!$this->getprice()) {
            return null;
        }
        // If no lifespan or it is 0, return full price
        if(!$this->getlifespan() || bccomp($this->lifespan, "0") < 1) {
            return number_format($this->getprice(), 2);
        }
        // If age is >= half of the lifespan, then give discounted price
        if(bccomp($this->getage(), bcdiv($this->getlifespan(), "2", 2), 2) >= 0) {
            return bcmul($this->price, self::OLD_PRODUCT_DISCOUNT, 2);
        } else {
            return number_format($this->price, 2);
        }
    }
    
    public function getOldProductDiscount() {
        return self::OLD_PRODUCT_DISCOUNT;
    }
    
    /**
     * Determines if object has sufficient data to save/update in the database.
     * @return boolean
     */
    public function isAllDataSet() {
        // Check this object has set values for id, pet_type, item_type, name, color, lifespan, age, price
        if($this->getpet_type() && $this->getitem_type() && $this->getname()
            && $this->getcolor() && $this->getlifespan() && $this->getage() && $this->getprice()) {
            return true;
        } else {
            return false;
        }
    }
}
