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
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'babel' => [],
        ]);
    }
}
