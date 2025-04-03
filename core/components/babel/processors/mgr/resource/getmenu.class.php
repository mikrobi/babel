<?php
/**
 * Get resource menu
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectProcessor;

class BabelResourceGetMenuProcessor extends ObjectProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $deletedCount = $this->modx->getCount($this->classKey, ['deleted' => true]);

        /** @var modResource $resource */
        $resource = $this->modx->getObject($this->classKey, $this->getProperty('id'));
        if ($resource) {
            $menu = $this->babel->getMenu($resource);
            if ($menu) {
                $output = [
                    'menu' => $menu,
                    'context_key' => $resource->get('context_key'),
                    'deletedCount' => $deletedCount,
                ];
                return $this->success('', $output);
            }
        }
        return $this->failure($this->modx->lexicon('babel.resource_err_invalid_id', [
            'resource' => $this->getProperty('id')
        ]), []);
    }
}

return 'BabelResourceGetMenuProcessor';
