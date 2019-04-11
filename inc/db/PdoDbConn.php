<?php
/* Code by: Nick Rolando */
namespace PetStoreInc\db;
use PetStoreInc\Helper;

/* Description: Implements mysql PDO functionality. */
class PdoDbConn
{
    private static $instance;
    private $_host;
    private $_db;
    private $_user;
    private $_pwd;
    private $_conn;
    private $_isDbOpen;
    
    /* contructor */
    private function __construct() {
        $this->_host = Helper::$dbHost;
        $this->_db = Helper::$dbName;
        $this->_user = Helper::$dbUser;
        $this->_pwd = Helper::$dbPw;
        
        $this->_conn = null;
        $this->_isDbOpen = false;
        
        $this->openDB();
    }
    
    /* destructor */
    public function __destruct() {
        $this->closeDB();
    }
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new PdoDbConn();
            return self::$instance;
        } else {
            return self::$instance;
        }
    }

    /**
     * Initialize $this->_conn to an open PDO connection. Should only be called in __construct()
     * @throws \Exception
     */
    private function openDB() {
        $connString = 'mysql:host='.$this->_host.';dbname='.$this->_db;
        try {
            $this->_conn = new \PDO($connString, $this->_user, $this->_pwd);
            $this->_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);   
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        $this->_isDbOpen = true;
    }
    
    /**
     * Close open PDO connection on $this->_conn. Currently only planning to call in __destruct()
     */
    private function closeDB() {
        if(isset($this->_conn)) {
            $this->_conn = null;
            $this->_isDbOpen = false;
        }
    }
    
    public function isDbOpen() {
        return $this->_isDbOpen;
    }
    
    /**
     * Use to query a table for a single row. Does not bind parameters. PDO Object http://php.net/manual/en/book.pdo.php
     * Should be called within a try/catch block.
     * @return Queried row or false when no row was found.
     * @throws \Exception
     */
    public function doQuery($sql){
        try {
            $stmt = $this->_conn->query($sql);
            if($stmt === false) {
                $errArr = $this->_conn->errorInfo();
                throw new \Exception($errArr[2]);
            }
            //$stmt->execute(array('batchId' => $batchId));
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        
        $stmt->closeCursor();
        
        return $row;
    }
    
    /**
     * Performs a parameterized SELECT query.
     * $query: SQL query where parameters to be bound are named or question marked
     * $para: An array of the parameters to be bound. If $query uses named parameters, then the array keys much match the named parameters.
     * @return array returned from PDOStatement::fetchAll()
     * @throws \Exception
     */
    public function doParaSelectQry($query, $para = array()) {
        try {
            $stmt = $this->_conn->prepare($query);
            if($stmt === false) {
                $errArr = $this->_conn->errorInfo();
                throw new \Exception($errArr[2]);
            }
            $stmt->execute($para);
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        
        $rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        
        return $rs;
    }

    /**
     * Performs a parameterized manipulation query. Throws Exception on failure.
     * 
     * @param query SQL query where parameters to be bound are named or question marked
     * @param para An array of the parameters to be bound. If $query uses named parameters, then the array keys much match the named parameters.
     * @throws \Exception
     */
    public function doParaManipQry($query, $para = array()) {
        try {
            $stmt = $this->_conn->prepare($query);
            if($stmt === false) {
                $errArr = $this->_conn->errorInfo();
                throw new \Exception($errArr[2]);
            }
            $stmt->execute($para);
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
