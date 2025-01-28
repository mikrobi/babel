<?php

namespace mikrobi\Babel\Processors\Context\Setting\V2;

require_once MODX_CORE_PATH . 'model/modx/processors/context/setting/remove.class.php';

class Remove extends \modContextSettingRemoveProcessor
{
    use \mikrobi\Babel\Processors\Context\Setting\CommonTrait;
    
    private $removeAction = 'Context/Setting/Remove';
    
    public function process() 
    {
        $resultProcess = parent::process();
        
        $isBabelAction = $this->removeAction == $this->getProperty('action');
        $isSyncOption = in_array($this->getProperty('key'), $this->getBabel()->config['syncOptions']);
        if($isBabelAction && $isSyncOption){
            $this->removeGroupContextSetting();
            $this->runOnSaveEvent($this, $this->modeUpd);
        }
        
        return $resultProcess;
    }
}
