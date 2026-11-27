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
        $contexts = [];
        foreach ($nodesAffected as $node) {
            $linkedResources = $this->babel->getLinkedResources($node->get('id'));
            foreach ($linkedResources as $key => $id) {
                if ($id === $node->get('id')) {
                    unset($linkedResources[$key]);
                }
            }
            $linkedResources[$node->get('context_key')] = $node->get('id');
            // Refresh the cache only once after all nodes are updated
            $this->babel->updateBabelTv($linkedResources, $linkedResources, false);
            $contexts = array_merge($contexts, array_keys($linkedResources));
        }
        if (!empty($contexts)) {
            $this->modx->cacheManager->refresh([
                'babel' => [],
                'resource' => ['contexts' => array_values(array_unique($contexts))],
            ]);
        }
    }
}
