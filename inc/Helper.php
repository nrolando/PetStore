<?php
namespace PetStoreInc;

class Helper
{
    public static $dbHost = "localhost";
    public static $dbName = "pet_store";
    public static $tblName_product = "products";
    public static $dbUser = "petstore_local";
    public static $dbPw = "h&85$%dUyI3";
    
    /**
     * echo help text
     */
    public static function runHelp() {
        echo "Usage: php cli/StoreCli.php --[command] [optional parameter 1] [optional parameter 2] [etc..]" . PHP_EOL;
        echo "Valid Commands: add, update, delete, list." . PHP_EOL;
        echo "Params for `add`: pet_type, item_type, name, color, lifespan, age, price" . PHP_EOL;
        echo "Params for `update`: id, pet_type, item_type, name, color, lifespan, age, price" . PHP_EOL;
        echo "Params for `delete`: id" . PHP_EOL;
        echo "Params for `list` (optional): filters [attribute]=[value],[attribute]=[value],... sort [attribute]" . PHP_EOL;
    }
}

