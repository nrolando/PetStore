<?php
namespace PetStoreInc\model\res;
use PetStoreInc\Helper;
use PetStoreInc\db\PdoDbConn;
use PetStoreInc\model\ModelProduct;

class CollectionProduct
{
    // DB Connection Instance
    private $dbConn;
    
    private $_collection;
    private $filters;
    private $sortby;
    private $validFilterKeys;
    private $validSortBy;
    
    public function __construct() {
        $this->filters = array();
        $this->sortby = '';
        $this->_collection = null;
        $this->dbConn = null;
        
        $this->validFilterKeys = array('pet_type', 'item_type', 'color');
        $this->validSortBy = array('pet_type', 'item_type', 'name', 'color', 'lifespan', 'age', 'price');
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
        $this->dbConn = PdoDbConn::getInstance();
        if(!$this->dbConn->isDbOpen()) {
            $this->dbConn->__openDB();
        }
        
        if(!empty($filters)) {
            // Set filters
            if(!is_array($filters)) {
                $filters = explode(',', $filters);
            }
            foreach($filters as $f) {
                $key = strstr($f, ',', true);
                if(!$this->isFilterKeyValid($key)) { throw new \Exception("Invalid filter key provided."); }
                $this->filters[$key] = substr($f, strpos($f, '='));
                if($this->filters[$key] === false || empty($this->filters[$key])) { throw new \Exception("No filter value provided."); }
            }
        }
        if(!empty($sortby)) {
            if(!$this->isSortByValid($sortby)) { throw new \Exception("Invalid Sort By value."); }
            $this->sortby = $sortby;
        }
        
        // Build SQL query
        $sql = "SELECT * FROM " . Helper::$tblName_product . " ";
        $sqlParamArray = array();
        if(!empty($this->filters)) {
            $sql .= "WHERE ";
            foreach($this->filters as $k => $f) {
                $sql .= $k . " = :" . $k . " AND ";
                $sqlParamArray[$k] = $f;
            }
            $sql = substr($sql, 0, strrpos($sql, " AND "));
        }
        if(!empty($this->sortby)) {
            $sql .= " ORDER BY :sortby";
            $sqlParamArray['sortby'] = $this->sortby;
        }
        
        //////do para select query....
    }
}
