<?php

namespace mikrobi\Babel\Processors\Context\Setting\V3;

use MODX\Revolution\modSystemEvent;

class Update extends \MODX\Revolution\Processors\Context\Setting\Update
{
    use \mikrobi\Babel\Processors\Context\Setting\CommonTrait;

    public function process() 
    {
        $resultProcess = parent::process();
        $this->runOnSaveEvent($this, modSystemEvent::MODE_UPD);

        return $resultProcess;
    }
}