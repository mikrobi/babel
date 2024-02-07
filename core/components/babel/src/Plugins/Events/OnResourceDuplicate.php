<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;

class OnResourceDuplicate extends Plugin
{
    /**
     * Check if the context of the current resource is referenced in babel.contextKeys
     * @return bool
     */
    public function init()
    {
        $resource = &$this->scriptProperties['newResource'];
        if (!$resource || !in_array($resource->get('context_key'), $this->babel->getOption('contexts'))) {
            return false;
        }

        return parent::init();
    }

    /**
     * Init Babel TV of duplicated resources
     * @return void
     */
    public function process()
    {
        $resource = &$this->scriptProperties['newResource'];
        $this->babel->initBabelTvsRecursive($this->modx, $this->babel, $resource->get('id'));
    }
}
