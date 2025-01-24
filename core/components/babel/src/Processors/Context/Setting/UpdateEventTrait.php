<?php

namespace mikrobi\Babel\Processors\Context\Setting;

use \mikrobi\Babel\Babel;

trait UpdateEventTrait
{
    private $babel = null;
    
    public function runOnSaveEvent($instance, $params)
    {
        $contextKey = $instance->object->get('context_key');
        $context = $this->modx->getObject('modContext', array('key' => $contextKey));
        if(!$context) {
            return;
        }
        $instance->modx->invokeEvent('OnContextSave', [
            'context' => $context,
            'mode' => $params,
        ]);
    }
    
    public function getBabel() 
    {
        if(!$this->babel) {
            $corePath = $this->modx->getOption('babel.core_path', null, $this->modx->getOption('core_path') . 'components/babel/');
            $this->babel = $this->modx->getService('babel', 'Babel', $corePath . 'model/babel/');
        }
        
        return $this->babel;
    }
}