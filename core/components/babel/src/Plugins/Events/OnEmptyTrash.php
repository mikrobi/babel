<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;

class OnEmptyTrash extends Plugin
{
    /**
     * Remove translation links to non-existent resources
     * @return void
     */
    public function process()
    {
        $deletedResourceIds = $this->scriptProperties['ids'];
        if (is_array($deletedResourceIds)) {
            // Remove translation links to non-existing resources
            foreach ($deletedResourceIds as $deletedResourceId) {
                $this->babel->removeLanguageLinksToResource($deletedResourceId);
            }
        }
    }
}
