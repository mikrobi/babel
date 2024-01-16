<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;
use modSystemEvent;

class OnDocFormSave extends Plugin
{
    /**
     * Check if the context of the current resource is referenced in babel.contextKeys
     * @return bool
     */
    public function init()
    {
        $resource = &$this->scriptProperties['resource'];
        if (!$resource || !in_array($resource->get('context_key'), $this->babel->getOption('contexts'))) {
            return false;
        }

        return parent::init();
    }

    /**
     * Initialize or Synchronize the TVs
     * @return void
     */
    public function process()
    {
        $resource = &$this->scriptProperties['resource'];
        if ($this->scriptProperties['mode'] == modSystemEvent::MODE_NEW) {
            // No TV synchronization for new resources, just init Babel TV
            $this->babel->initBabelTv($resource);
            return;
        }
        $this->babel->synchronizeTvs($resource->get('id'));
    }
}
