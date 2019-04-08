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
     * throws new Exception on failure
     */
    public function run() {
        $this->parseArgs();
        $this->validateAndParseArgs();
        
        switch($this->cmd) {
            case 'add':
                $pm = new ModelProduct();
                $pm->setData(array(
                    'petType' => $this->cmdParams[0],
                    'itemType' => $this->cmdParams[1],
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
                    'petType' => $this->cmdParams[1],
                    'itemType' => $this->cmdParams[2],
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
                $pm = new ModelProduct();
                $pm->load($this->cmdParams[0]);
                $pm->deleteAndUnload();
                echo "Deleted product " . $this->cmdParams[0] . PHP_EOL;
                break;
            case 'list':
                $products = new \PetStoreInc\model\res\CollectionProduct();
                $pm = new \PetStoreInc\model\ModelProduct();
                $products->loadCollection($this->arg_list_filters, $this->arg_list_sort);
                foreach($products->getCollection() as $prod) {
                    $pm->load($prod['id']);
                    echo sprintf("%-25s", "Name: " . $prod['name'] . ";") . sprintf("%-20s", "Item Type: " . $prod['item_type'] . ";")
                        . sprintf("%-20s", "Pet Type: " . $prod['pet_type'] . ";") . sprintf("%-15s", "Price: $" . $pm->getPrice() . ";")
                        . sprintf("%-20s", "Color: " . $prod["color"] . ";") . PHP_EOL;
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
        
        // Validate Command Parameters (minimum validation.. just check for correct number of arguments
        switch($this->cmd) {
            case 'add':
                if(count($this->cmdParams) !== 7) { $areParamsValid = false; }
                break;
            case 'update':
                if(count($this->cmdParams) !== 8) { $areParamsValid = false; }
                break;
            case 'delete':
                if(count($this->cmdParams) !== 1) { $areParamsValid = false; }
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
