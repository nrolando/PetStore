<?php
/**
 * Code by: Nick Rolando
 */
namespace PetStoreInc\model;
use PetStoreInc\Helper;
use PetStoreInc\db\PdoDbConn;

class ModelProduct extends ModelAbstract
{
    private const OLD_PRODUCT_DISCOUNT = .5;
    
    /**
     * Add a product to the db.
     * @throws \Exception
     */
    public function save() {
        if(!isset($this->petType) || !isset($this->itemType) || !isset($this->name) || !isset($this->color) || !isset($this->lifespan) || !isset($this->age) || !isset($this->price)) {
            throw new \Exception("Cannot save product due to insufficient data.");
        }
        
        $dbConn = PdoDbConn::getInstance();
        
        if(isset($this->id)) {
            //Update product
            $query = "UPDATE " . $this->getDbNameTbl() . " SET `name` = :name, pet_type = :petType, item_type = :itemType, color = :color, "
                . "lifespan = :lifespan, age = :age, price = :price WHERE `id` = " . $this->id;
            $dbConn->doParaManipQry($query, array(
                'name' => $this->name,
                'petType' => $this->petType,
                'itemType' => $this->itemType,
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
                'petType' => $this->petType,
                'itemType' => $this->itemType,
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
        $this->petType = $r['pet_type'];
        $this->itemType = $r['item_type'];
        $this->color = $r['color'];
        $this->lifespan = $r['lifespan'];
        $this->age = $r['age'];
        $this->price = $r['price'];
        
        return $this;
    }
    
    /**
     * Deletes product model entity that has been loaded with ->load($id)
     * and resets product attributes to null.
     */
    public function delete() {
        if(isset($this->id)) {
            $dbConn = PdoDbConn::getInstance();
            $query = "DELETE FROM " . $this->getDbNameTbl() . " WHERE `id` = " . $this->id;
            $dbConn->doParaManipQry($query);
        }
        
        $this->setData();
    }
    
    private function getDbNameTbl() {
        return "`" . Helper::$dbName . "`.`" . Helper::$tblName_product . "`";
    }
    
    public function getPrice() {
        if(!isset($this->lifespan) || $this->lifespan == 0) {
            return $this->price;
        }
        if($this->age > ($this->lifespan/2)) {
            return ($this->price * self::OLD_PRODUCT_DISCOUNT);
        } else {
            return $this->price;
        }
    }
}
