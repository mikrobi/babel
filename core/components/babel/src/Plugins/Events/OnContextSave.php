<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;
use modContext;
use modContextSetting;

class OnContextSave extends Plugin
{
    /**
     * Refresh the babel cache and sync the grouped context settings
     * @return void
     */
    public function process()
    {
        // Sync grouped context settings
        $context = &$this->scriptProperties['context'];
        $syncedContextKeys = $this->syncContextSettings($context);

        // Refresh the context_settings, resource and babel cache
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'context_settings' => ['contexts' => $syncedContextKeys],
            'resource' => ['contexts' => $syncedContextKeys],
            'babel' => [],
        ]);
    }

    /**
     * @param modContext $currentContext
     * @return array
     */
    private function syncContextSettings($currentContext)
    {
        if (!$currentContext) {
            return [];
        }
        $contextKeysToGroup = isset($this->babel->contextKeyToGroup[$currentContext->get('key')]) ? $this->babel->contextKeyToGroup[$currentContext->get('key')] : [];

        $syncedContextKeys = [];
        foreach ($contextKeysToGroup as $contextKey) {
            if ($contextKey == $currentContext->get('key')) {
                continue;
            }
            foreach ($this->babel->syncedContextSettings as $syncedSettingKey) {
                $currentContextSetting = $this->modx->getObject('modContextSetting', [
                    'context_key' => $currentContext->get('key'),
                    'key' => $syncedSettingKey,
                ]);
                if (!$currentContextSetting) {
                    continue;
                }

                $contextSetting = $this->modx->getObject('modContextSetting', [
                    'context_key' => $contextKey,
                    'key' => $syncedSettingKey,
                ]);
                if (!$contextSetting) {
                    /** @var modContextSetting $contextSetting */
                    $contextSetting = $this->modx->newObject('modContextSetting');
                    $contextSetting->fromArray([
                        'context_key' => $contextKey,
                        'key' => $syncedSettingKey,
                        'value' => $currentContextSetting->get('value'),
                        'xtype' => 'textfield',
                        'namespace' => 'babel',
                        'area' => 'system',
                    ], '', true, true);
                } else {
                    $contextSetting->set('value', $currentContextSetting->get('value'));
                }
                $contextSetting->save();
                $syncedContextKeys[] = $contextKey;
            }
        }
        return $syncedContextKeys;
    }
}
