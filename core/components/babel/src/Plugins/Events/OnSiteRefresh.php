<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;
use xPDO;

class OnSiteRefresh extends Plugin
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
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.refresh_cache', [
            'packagename' => $this->babel->packageName
        ]));
    }
}
