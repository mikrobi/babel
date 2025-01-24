<?php

namespace mikrobi\Babel\Processors\Context\Setting;

use MODX\Revolution\modSystemEvent;

class Remove extends \MODX\Revolution\Processors\Context\Setting\Remove
{
    use \mikrobi\Babel\Processors\Context\Setting\UpdateEventTrait;
    
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
    
    private function removeGroupContextSetting()
    {
        $currentCtx = $this->object->get('context_key');
        $keysToGroup = $this->babel->contextKeyToGroup[$currentCtx];
        
        if(is_array($keysToGroup)) {
            foreach($keysToGroup as $context) {
                if($currentCtx == $context) continue;
                
                $removeContextSetting = $this->modx->getObject('modContextSetting', [
                    'context_key' => $context,
                    'key' => $this->getProperty('key'),
                ]);
                if(!$removeContextSetting) continue;
                
                $removeContextSetting->remove();
            }
        }
    }
}
