<?php
/* Code by: Nick Rolando */
namespace PetStoreInc\db;
use PetStoreInc\Helper;

/* Description: Implements mysql PDO functionality. */
class PdoDbConn
{
    private static $instance;
    private static $_host;
    private static $_db;
    private static $_user;
    private static $_pwd;
    private static $_conn;
    public static $_isDbOpen;
    
    /* contructor */
    private function __construct() {
        self::$_host = Helper::$dbHost;
        self::$_db = Helper::$dbName;
        self::$_user = Helper::$dbUser;
        self::$_pwd = Helper::$dbPw;
        
        self::$instance = null;
        self::$_conn = null;
        self::$_isDbOpen = false;
    }
    
    /* destructor */
    public function __destruct() {
        self::closeDB();
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
     * Initialize $this->_conn to an open PDO connection.
     * throws Exception on failure
     */
    public static function __openDB(){
        $connString = 'mysql:host='.self::$_host.';dbname='.self::$_db;
        try {
            self::$_conn = new \PDO($connString, self::$_user, self::$_pwd);
            self::$_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);   
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        self::$_isDbOpen = true;
    }
    
    public static function closeDB()
    {
        if(isset(self::$_conn)) {
            self::$_conn = null;
            self::$_isDbOpen = false;
        }
    }
    
    /* doQuery() - PDO Object http://php.net/manual/en/book.pdo.php
     * Use to query a table for a single row. Does not bind parameters.
     * Should be called within a try/catch block.
     * Return: Queried row or false when no row was found.
     * Throws: Exception on failure */
    public static function doQuery($sql){
        try {
            $stmt = self::$_conn->query($sql);
            if($stmt === false) {
                $errArr = self::$_conn->errorInfo();
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
    
    /* Performs a parameterized SELECT query.
     * $query: SQL query where parameters to be bound are named or question marked
     * $para: An array of the parameters to be bound. If $query uses named parameters, then the array keys much match the named parameters.
     * Returns: ??
     * throws Exception on failure
     */
    public static function doParaSelectQry($query, $para = array()) {
        /*try {
            $stmt = self::$_conn->prepare($query);
            if($stmt === false) {
                $errArr = self::$_conn->errorInfo();
                self::$_err_msg = $errArr[2];
                return false;
            }
            $stmt->execute($para);
        } catch(\PDOException $e) {
            self::$_err_msg = $e->getMessage();
            return false;
        }*/
        
        // TO-DO: Build collection of rows to return and close PDOStatement $stmt
    }

    /**
     * Performs a parameterized manipulation query. Throws Exception on failure.
     * 
     * @param query SQL query where parameters to be bound are named or question marked
     * @param para An array of the parameters to be bound. If $query uses named parameters, then the array keys much match the named parameters.
     */
    public static function doParaManipQry($query, $para = array()) {
        try {
            $stmt = self::$_conn->prepare($query);
            if($stmt === false) {
                $errArr = self::$_conn->errorInfo();
                throw new \Exception($errArr[2]);
            }
            $stmt->execute($para);
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
