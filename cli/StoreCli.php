<?php
/**
 * Code by: Nick Rolando
 * Desc: Run script to query or manipulate store database (check --help command for assistance)
 */
namespace PetStoreCli;
require_once dirname(__dir__) . '\vendor\autoload.php';
use PetStoreInc\Helper;
use PetStoreInc\model\ModelProduct;

class StoreCli extends CliAbstract
{
    private $arg_list_filters;
    private $arg_list_sort;
    
    public function __construct() {
        parent::__construct();
        
        $this->arg_list_filters = '';
        $this->arg_list_sort = '';

        $this->validCommands = array('add', 'update', 'delete', 'list', 'help');
    }

    /**
     * @throws \Exception
     */
    public function run() {
        $this->parseArgs();
        $this->validateAndParseArgs();
        
        switch($this->cmd) {
            case 'add':
                $pm = new ModelProduct();
                $pm->setData(array(
                    'pet_type' => $this->cmdParams[0],
                    'item_type' => $this->cmdParams[1],
                    'name' => $this->cmdParams[2],
                    'color' => $this->cmdParams[3],
                    'lifespan' => $this->cmdParams[4],
                    'age' => $this->cmdParams[5],
                    'price' => $this->cmdParams[6]
                ));
                $pm->save();
                echo "Added product " . $this->cmdParams[2] . PHP_EOL;
                break;
            case 'update':
                $pm = new ModelProduct();
                $pm->load($this->cmdParams[0]);
                $pm->setData(array(
                    'pet_type' => $this->cmdParams[1],
                    'item_type' => $this->cmdParams[2],
                    'name' => $this->cmdParams[3],
                    'color' => $this->cmdParams[4],
                    'lifespan' => $this->cmdParams[5],
                    'age' => $this->cmdParams[6],
                    'price' => $this->cmdParams[7]
                ));
                $pm->save();
                echo "Updated product " . $this->cmdParams[0] . PHP_EOL;
                break;
            case 'delete':
                ModelProduct::deleteId($this->cmdParams[0]);
                echo "Deleted product " . $this->cmdParams[0] . PHP_EOL;
                break;
            case 'list':
                $products = new \PetStoreInc\model\res\CollectionProduct();
                $products->loadCollection($this->arg_list_filters, $this->arg_list_sort);
                foreach($products->getCollection() as $prod) {
                    echo sprintf("%-20s", "Item Type: " . $prod->getitem_type() . ";")
                        . sprintf("%-20s", "Pet Type: " . $prod->getpet_type() . ";") . sprintf("%-25s", "Name: " . $prod->getname() . ";")
                        . sprintf("%-20s", "Price: $" . $prod->getCalculatedPrice() . ";")
                        . sprintf("%-20s", "Color: " . $prod->getcolor() . ";")
                        . sprintf("%-20s", "Lifespan: " . $prod->getlifespan() . ";")
                        . sprintf("%-15s", "Age: " . $prod->getage() . ";"). PHP_EOL;
                }
                break;
            case 'help':
                Helper::runHelp();
                break;
            default:
                break;
        }
    }

    /**
     * throws new Exception on failure
     */
    public function validateAndParseArgs() {
        $areParamsValid = true;
        
        // Validate Command
        if(!in_array($this->cmd, $this->validCommands)) {
            throw new \Exception("Invalid Command '" . $this->cmd . "'.");
        }
        
        // Validate Command Parameters
        switch($this->cmd) {
            case 'add':
                // Param order should be: pet_type, item_type, name, color, lifespan, age, price
                if(count($this->cmdParams) !== 7) { $areParamsValid = false; }
                if(!is_numeric($this->cmdParams[4]) || !is_numeric($this->cmdParams[5]) || !is_numeric($this->cmdParams[6])) {
                    $areParamsValid = false;
                }
                // Do not allow negative values
                if($this->cmdParams[4][0] === "-" || $this->cmdParams[5][0] === "-" || $this->cmdParams[6][0] === "-") {
                    $areParamsValid = false;
                }
                break;
            case 'update':
                // Param order should be: id, pet_type, item_type, name, color, lifespan, age, price
                if(count($this->cmdParams) !== 8) { $areParamsValid = false; }
                if(!is_numeric($this->cmdParams[0]) || !is_numeric($this->cmdParams[5]) || !is_numeric($this->cmdParams[6]) || !is_numeric($this->cmdParams[7])) {
                    $areParamsValid = false;
                }
                // Do not allow negative values
                if($this->cmdParams[5][0] === "-" || $this->cmdParams[6][0] === "-" || $this->cmdParams[7][0] === "-") {
                    $areParamsValid = false;
                }
                break;
            case 'delete':
                if(count($this->cmdParams) !== 1) { $areParamsValid = false; }
                if(!is_numeric($this->cmdParams[0])) {
                    $areParamsValid = false;
                }
                break;
            case 'list':
                // 'list' can come with a 'sort' and/or 'filter parameter
                if(isset($this->cmdParams[0])) {
                    if($this->cmdParams[0] === "filters") {
                        $this->arg_list_filters = ((isset($this->cmdParams[1])) ? $this->cmdParams[1] : null);
                    } elseif($this->cmdParams[0] === "sort") {
                        $this->arg_list_sort = ((isset($this->cmdParams[1])) ? $this->cmdParams[1] : null);
                    } else {
                        $areParamsValid = false;
                    }
                }
                if(isset($this->cmdParams[2])) {
                    if($this->cmdParams[2] === "filters") {
                        $this->arg_list_filters = ((isset($this->cmdParams[3])) ? $this->cmdParams[3] : null);
                    } elseif($this->cmdParams[2] === "sort") {
                        $this->arg_list_sort = ((isset($this->cmdParams[3])) ? $this->cmdParams[3] : null);
                    } else {
                        $areParamsValid = false;
                    }
                }
                break;
            case 'help':
                break;
            default:
                throw new \Exception("This line should not have been reached; bug in code.");
        }
        
        if(!$areParamsValid) {
            throw new \Exception("Invalid parameters passed to script.");
        }
    }
}

try {
    $sc = new StoreCli();
    $sc->run();
} catch(\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    Helper::runHelp();
}
echo "Goodbye" . PHP_EOL;
