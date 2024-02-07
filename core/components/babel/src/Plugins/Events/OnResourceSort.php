<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;

class OnResourceSort extends Plugin
{
    /**
     * Update Babel TV of sorted resources
     * @return void
     */
    public function process()
    {
        $nodesAffected = &$this->scriptProperties['nodesAffected'];
        foreach ($nodesAffected as $node) {
            $linkedResources = $this->babel->getLinkedResources($node->get('id'));
            foreach ($linkedResources as $key => $id) {
                if ($id === $node->get('id')) {
                    unset($linkedResources[$key]);
                }
            }
            $linkedResources[$node->get('context_key')] = $node->get('id');
            $this->babel->updateBabelTv($linkedResources, $linkedResources);
        }
    }
}
