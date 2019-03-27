<?php
/* Code by: Nick Rolando */
namespace PetStoreShell;

abstract class ShellAbstract
{
    protected $cmd;
    protected $cmdParams;
    protected $validCommands;
    
    public function __construct() {
        $this->cmdParams = array();
    }
    
    protected function parseArgs() {
        /* Known bug with filter_input(INPUT_SERVER,*,*): https://bugs.php.net/bug.php?id=49184 */
        //$args = filter_input(INPUT_SERVER, 'argv', FILTER_SANITIZE_STRING);
        $args = $_SERVER['argv'];
        
        if(!isset($args[1]) || substr($args[1], 0, 2) !== '--') {
            throw new \Exception("Invalid Arguments.");
        }
        
        // Get command
        $this->cmd = substr($args[1], 2);
        
        // Get command parameters
        $i = 2;
        while(isset($args[$i]) && $args[$i] !== "") {
            $this->cmdParams[] = $args[$i];
            $i++;
        }
    }
    
    abstract public function run();
    abstract public function validateArgs();
}
