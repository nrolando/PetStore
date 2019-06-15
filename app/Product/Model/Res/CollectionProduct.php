<?php
namespace PetStoreApp\Product\Model\Res;
use PetStoreApp\Core\Model\db\PdoDbConn;
use PetStoreApp\Core\Helper;
use PetStoreApp\Product\Model\ModelProduct;

class CollectionProduct
{
    // DB Connection Instance
    private $dbConn;
    
    // An associative array containing queries product collection
    private $_collection;
    
    private $filters;
    private $sortby;
    private $validFilterKeys;
    private $validSortBy;
    
    public function __construct(array $p_products = []) {
        $this->filters = array();
        $this->sortby = '';
        $this->dbConn = PdoDbConn::getInstance();
        $this->_collection = $p_products;
        
        $this->validFilterKeys = array('pet_type', 'item_type', 'color');
        $this->validSortBy = array('id', 'pet_type', 'item_type', 'name', 'color', 'lifespan', 'age', 'price');
    }
    
    public function getCollection() {
        return $this->_collection;
    }
    
    private function isFilterKeyValid($key) {
        return (in_array($key, $this->validFilterKeys));
    }
    
    private function isSortByValid($v) {
        return (in_array($v, $this->validSortBy));
    }
    
    /**
     * 
     * @param type $filters Comma separated with key=value filters (e.g. color=green,pet_type=Dog)
     * @param type $sortby Attribute to sort by, e.g. price
     */
    public function loadCollection($filters = '', $sortby = 'id') {
        if(!empty($filters)) {
            // Set filters
            if(!is_array($filters)) {
                $filters = explode(',', $filters);
            }
            foreach($filters as $f) {
                $key = strstr($f, '=', true);
                if(!$this->isFilterKeyValid($key)) { throw new \Exception("Invalid filter key provided."); }
                $this->filters[$key] = substr($f, (strpos($f, '=') + 1));
                if($this->filters[$key] === false || empty($this->filters[$key])) { throw new \Exception("No filter value provided."); }
            }
        }
        
        // Build SQL query
        $sql = "SELECT `id`, `name`, pet_type, item_type, `color`, `lifespan`, `age`, `price` FROM " 
            . $this->getProductModelTblName() . " ";
        $sqlParamArray = array();
        if(!empty($this->filters)) {
            $sql .= "WHERE ";
            foreach($this->filters as $k => $f) {
                $sql .= $k . " = :" . $k . " AND ";
                $sqlParamArray[$k] = $f;
            }
            $sql = substr($sql, 0, strrpos($sql, " AND "));
        }
        
        $rs = $this->dbConn->doParaSelectQry($sql, $sqlParamArray);
        
        foreach($rs as $row) {
            $mp = new ModelProduct();
            $this->_collection[] = $mp->setData($row);
        }
        // free-up a potentially large array
        unset($rs);
        
        // Sorting in PHP vs MySQL ORDER BY
        if(!empty($sortby)) {
            if(!$this->isSortByValid($sortby)) { throw new \Exception("Invalid Sort By value."); }
            $this->sortby = $sortby;
        }
        $this->sortCollection();
    }
    
    /**
     * Sorts collection based on $this->sortby attribute
     * @return boolean true on success or false on failure
     */
    private function sortCollection() {
        if(empty($this->sortby) || count($this->_collection) < 1) {
            return false;
        } else {
            if($this->sortby === 'price') {
                usort($this->_collection, array($this, "usortByPrice"));
            } elseif($this->sortby === 'age') {
                usort($this->_collection, array($this, "usortByAge"));
            } elseif($this->sortby === 'lifespan') {
                usort($this->_collection, array($this, "usortByLifespan"));
            } elseif($this->sortby === 'name') {
                usort($this->_collection, array($this, "usortByName"));
            } elseif($this->sortby === 'pet_type') {
                usort($this->_collection, array($this, "usortByPetType"));
            } elseif($this->sortby === 'item_type') {
                usort($this->_collection, array($this, "usortByItemType"));
            } elseif($this->sortby === 'color') {
                usort($this->_collection, array($this, "usortByColor"));
            }
        }
    }
    
    private function getProductModelTblName() {
        return "`" . Helper::$dbName . "`.`" . Helper::$tblName_product . "`";
    }
    
    public static function usortByPrice($a, $b) {
        return $a->getCalculatedPrice() > $b->getCalculatedPrice();
    }
    public static function usortByAge($a, $b) {
        return (double)$a->getage() > (double)$b->getage();
    }
    public static function usortByLifespan($a, $b) {
        return (double)$a->getlifespan() > (double)$b->getlifespan();
    }
    public static function usortByName($a, $b) {
        return $a->getname() > $b->getname();
    }
    public static function usortByPetType($a, $b) {
        return $a->getpet_type() > $b->getpet_type();
    }
    public static function usortByItemType($a, $b) {
        return $a->getitem_type() > $b->getitem_type();
    }
    public static function usortByColor($a, $b) {
        return $a->getcolor() > $b->getcolor();
    }
}
