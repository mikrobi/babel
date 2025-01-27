<?php

namespace mikrobi\Babel\Processors\Context\Setting\V2;

require_once MODX_CORE_PATH . 'model/modx/processors/context/setting/update.class.php';

class Update extends \modContextSettingUpdateProcessor
{
    use \mikrobi\Babel\Processors\Context\Setting\CommonTrait;
    
    public function process() 
    {
        $resultProcess = parent::process();
        $this->runOnSaveEvent($this, $this->modeUpd);
        
        return $resultProcess;
    }
}
