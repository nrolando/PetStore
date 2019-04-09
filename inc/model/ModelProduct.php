<?php
namespace PetStoreInc\model;
use PetStoreInc\Helper;
use PetStoreInc\db\PdoDbConn;

class ModelProduct
{
    // DB Connection Instance
    private $dbConn;
    
    // Product Attributes
    private $id;
    private $petType;
    private $itemType;
    private $name;
    private $color;
    private $lifespan;
    private $age;
    private $price;
    
    private const OLD_PRODUCT_DISCOUNT = .5;
    
    public function __construct() {
        $this->dbConn = null;
        $this->id = null;
        $this->petType = null;
        $this->itemType = null;
        $this->name = null;
        $this->color = null;
        $this->lifespan = null;
        $this->age = null;
        $this->price = null;
    }
    
    /* Set all product attributes.
     * param: Must be array with keys: 'petType', 'itemType', 'name', 'color', 'lifespan', 'age', 'price'
     * throws Exception on error
     */
    public function setData(array $pd) {
        if(!isset($pd['petType']) || !isset($pd['itemType']) || !isset($pd['name']) || !isset($pd['color']) || !isset($pd['lifespan']) || !isset($pd['age']) || !isset($pd['price'])) {
            throw new \Exception("Invalid parameters passed to \\PetStoreInc\\model\\ModelProduct::setData()");
        }
        
        $this->petType = $pd['petType'];
        $this->itemType = $pd['itemType'];
        $this->name = $pd['name'];
        $this->color = $pd['color'];
        $this->lifespan = $pd['lifespan'];
        $this->age = $pd['age'];
        $this->price = $pd['price'];
        
        return $this;
    }
    
    /* Desc: Add a product to the db.
     * param: array('pet_type', 'item_type', 'name', 'color', 'lifespan', 'age', 'price')
     * throws Exception on error.
     */
    public function save() {
        if(!isset($this->petType) || !isset($this->itemType) || !isset($this->name) || !isset($this->color) || !isset($this->lifespan) || !isset($this->age) || !isset($this->price)) {
            throw new \Exception("Cannot save product due to insufficient data.");
        }
        
        // Get DB connection instance
        if(is_null($this->dbConn)) {
            $this->dbConn = PdoDbConn::getInstance();
        }
        
        if(isset($this->id)) {
            //Update product
            $query = "UPDATE " . $this->getDbNameTbl() . " SET `name` = :name, pet_type = :petType, item_type = :itemType, color = :color, "
                . "lifespan = :lifespan, age = :age, price = :price WHERE `id` = " . $this->id;
            $this->dbConn->doParaManipQry($query, array(
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
            $this->dbConn->doParaManipQry($query, array(
                'name' => $this->name,
                'petType' => $this->petType,
                'itemType' => $this->itemType,
                'color' => $this->color,
                'lifespan' => $this->lifespan,
                'age' => $this->age,
                'price' => $this->price
            ));
            $query = "SELECT LAST_INSERT_ID() as last_id;";
            $r = $this->dbConn->doQuery($query);
            $this->id = $r['last_id'];
        }
        
        return $this;
    }
    
    /* Desc: Select product by id paramater and load object
     * param: id of product to load
     */
    public function load($id) {
        // Get DB connection instance
        if(is_null($this->dbConn)) {
            $this->dbConn = PdoDbConn::getInstance();
        }
        
        $query = "SELECT `name`, pet_type, item_type, color, lifespan, age, `price` FROM "
            . $this->getDbNameTbl() . " WHERE `id` = " . $id;
        $r = $this->dbConn->doQuery($query);
        
        if(!is_array($r) || empty($r)) {
            throw new \Exception("Product id " . $id . " not found.");
        }
        
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
    
    public function deleteAndUnload() {
        if(isset($this->id)) {
            $query = "DELETE FROM " . $this->getDbNameTbl() . " WHERE `id` = " . $this->id;
            $this->dbConn->doParaManipQry($query);
        }
        $this->petType = null;
        $this->itemType = null;
        $this->name = null;
        $this->color = null;
        $this->lifespan = null;
        $this->age = null;
        $this->price = null;
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
