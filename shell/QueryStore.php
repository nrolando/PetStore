<?php
/* Code by: Nick Rolando */
namespace PetStoreShell;
require_once dirname(__dir__) . '\vendor\autoload.php';
use PetStoreInc\model\ModelProduct;

class QueryStore extends ShellAbstract
{
    public function __construct() {
        parent::__construct();

        $this->validCommands = array('add', 'update', 'delete', 'list');
    }

    /* throws new Exception on failure
     */
    public function run() {
        $this->parseArgs();
        $this->validateArgs();
        
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
                break;
            default:
                break;
        }
    }

    /* throws new Exception on failure
     */
    public function validateArgs() {
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
                break;
            default:
                throw new \Exception("This line should not have been reached; bug in code.");
        }
        
        if(!$areParamsValid) {
            throw new \Exception("Invalid parameters passed to script.");
        }
    }
}

function runHelp() {
    echo "Usage: php QueryStore.php --[command] [optional parameter 1] [optional parameter 2] [etc..]" . PHP_EOL;
    echo "Valid Commands: add, update, delete, list." . PHP_EOL;
    echo "Params for `add`: pet_type, item_type, name, color, lifespan, age, price" . PHP_EOL;
    echo "Params for `update`: id, pet_type, item_type, name, color, lifespan, age, price" . PHP_EOL;
    echo "Params for `delete`: id" . PHP_EOL;
}

try {
    $qs = new QueryStore();
    $qs->run();
} catch(\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    runHelp();
}
echo "Goodbye" . PHP_EOL;
