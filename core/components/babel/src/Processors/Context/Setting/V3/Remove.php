<?php

namespace mikrobi\Babel\Processors\Context\Setting\V3;

use MODX\Revolution\modSystemEvent;

class Remove extends \MODX\Revolution\Processors\Context\Setting\Remove
{
    use \mikrobi\Babel\Processors\Context\Setting\CommonTrait;

    const REMOVE_ACTION = 'Context/Setting/Remove';

    public function process() 
    {
        $resultProcess = parent::process();

        $isBabelAction = self::REMOVE_ACTION == $this->getProperty('action');
        $isSyncOption = in_array($this->getProperty('key'), $this->getBabel()->config['syncOptions']);
        if($isBabelAction && $isSyncOption){
            $this->removeGroupContextSetting();
            $this->runOnSaveEvent($this, modSystemEvent::MODE_UPD);
        }

        return $resultProcess;
    }
}