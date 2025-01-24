<?php

namespace mikrobi\Babel\Processors\Context\Setting;

use MODX\Revolution\modSystemEvent;

class UpdateFromGrid extends \MODX\Revolution\Processors\Context\Setting\UpdateFromGrid
{
    
    use \mikrobi\Babel\Processors\Context\Setting\UpdateEventTrait;
    
    public function process() 
    {
        $resultProcess = parent::process();
        $this->runOnSaveEvent($this, modSystemEvent::MODE_UPD);
        
        return $resultProcess;
    }
}
