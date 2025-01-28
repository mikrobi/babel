<?php
use mikrobi\Babel\Processors\ObjectProcessor;

class BabelContextFactoryProcessor extends ObjectProcessor
{
    public $runFactoryClass = null;
    public function run()
    {
        $factoryClassName = "mikrobi\\Babel\\Processors\\Context\\Setting\\V" . $this->modx->version['version'] . "\\" . $this->runFactoryClass;
        
        if(!class_exists($factoryClassName)) return;
        $processor = $factoryClassName::getInstance($this->modx,  $factoryClassName, $this->properties);
        return $processor->run();
    }
    
    public function process() {
        return;
    }
}

return 'BabelContextFactoryProcessor';