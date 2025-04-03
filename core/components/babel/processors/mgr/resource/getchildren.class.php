<?php
/**
 * Get list resource children
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\Processor;

class BabelResourceGetChildrenProcessor extends Processor
{
    public $permission = 'view';

    protected $search = ['pagetitle'];

    public function process() {
        $id = intval( $this->getProperty('id'));
        $ids = [$id];
        if ($this->getBooleanProperty('getchildren')) {
            $resource = $this->modx->getObject('modResource', $id);
            if ($resource) {
                $childIds = $this->modx->getChildIds($this->getProperty('id'), 10, [
                    'context' => $resource->get('context_key'),
                ]);
                if ($childIds) {
                    $ids = array_merge($ids, $childIds);
                }
            }
        }

        return $this->success('', [
            'child_ids' => $ids,
        ]);
    }

}

return 'BabelResourceGetChildrenProcessor';
