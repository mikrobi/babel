<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;

class OnContextSave extends Plugin
{
     /**
     * Refresh the babel cache
     * @return void
     */
    public function process()
    {
        $context = &$this->scriptProperties['context'];
        $this->syncSetting($context);
        
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'babel' => [],
        ]);
    }
    
    private function syncSetting($currentCtx) {
        if(!$currentCtx) return;
        $keysToGroup = $this->babel->contextKeyToGroup[$currentCtx->get('key')];
        
        foreach($keysToGroup as $context_key) {
            if($context_key == $currentCtx->get('key')) continue;
            foreach($this->babel->config['syncOptions'] as $key) {
                $currentCtxSetting = $this->modx->getObject('modContextSetting', [
                    'context_key' => $currentCtx->get('key'),
                    'key' => $key,
                ]);
                if(!$currentCtxSetting) continue;
                
                $ctxSetting = $this->modx->getObject('modContextSetting', [
                    'context_key' => $context_key,
                    'key' => $key,
                ]);
                if(!$ctxSetting) {
                    
                    $ctxSetting = $this->modx->newObject('modContextSetting', [
                        'value' => $currentCtxSetting->get('value'),
                        'xtype' => 'textfield',
                        'namespace' => 'babel',
                    ]);
                    //context_key и key устанавливаются толко через set. Возможно у модели происходят какие то проверки при установке через set. Форумы ответов не дали.
                    $ctxSetting->set('context_key', $context_key);
                    $ctxSetting->set('key', $key);
                    
                } else {
                    $ctxSetting->set('value', $currentCtxSetting->get('value'));
                }
                $ctxSetting->save();
            }
        }
    }
}
