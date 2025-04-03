<?php
/**
 * Delete resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelResourceDeleteProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];

    /** @var modResource $object */
    public $object;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $success = parent::initialize();

        $contextKey = $this->getProperty('context_key', false);
        if (!empty($contextKey)) {
            $context = $this->modx->getObject('modContext', ['key' => $contextKey]);
            if (!$context) {
                return $this->modx->lexicon('babel.context_err_invalid_key', [
                    'context' => $contextKey
                ]);
            }
        }

        return $success;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        if (empty($linkedResources)) {
            // Always be sure that the Babel TV is set
            $linkedResources = $this->babel->initBabelTv($this->object);
        }

        $contextKey = $this->getProperty('context_key');
        if (empty($contextKey)) {
            // Move all linked resources to the trash
            foreach ($linkedResources as $linkedResource) {
                foreach ($this->babel->getLinkedResources($linkedResource) as $resourceId) {
                    /** @var modResource $resource */
                    $resource = $this->modx->getObject('modResource', $resourceId);
                    if ($resource && $resource->get('id') !== $this->object->get('id')) {
                        $resource->set('deleted', true);
                        $resource->set('deletedon', time());
                        $resource->set('deletedby', $this->modx->user->id);
                        $resource->save();
                    }
                }
            }
            $this->babel->updateBabelTv($this->object->get('id'), []);
            $this->fireUnlinkEvent();

            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.success_delete_resources', [
                'id' => $this->object->get('id'),
            ]));
        } else {
            $target = $linkedResources[$contextKey];
            /** @var modResource $targetResource */
            $targetResource = $this->modx->getObject('modResource', $target);
            if (!$targetResource) {
                return $this->failure($this->modx->lexicon('babel.resource_err_invalid_id', [
                    'resource' => $target
                ]));
            }
            $targetResources = $this->babel->getLinkedResources($target);
            if (empty($targetResources)) {
                $targetResources = $this->babel->initBabelTv($targetResource);
            }
            unset($targetResources[$this->object->get('context_key')]);
            $this->babel->updateBabelTv($targetResources, $targetResources);
            unset($linkedResources[$this->getProperty('context_key')]);
            $this->babel->updateBabelTv($this->object->get('id'), $linkedResources);
            $this->fireUnlinkEvent($targetResource);
            $targetResource->set('deleted', true);
            $targetResource->set('deletedon', time());
            $targetResource->set('deletedby', $this->modx->user->id);
            $targetResource->save();

            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.success_delete_resource', [
                'id' => $this->object->get('id'),
                'oldid' => $targetResource->get('id'),
                'context' => $this->getProperty('context_key'),
            ]));
        }
        if ($this->getBooleanProperty('last')) {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'COMPLETED');
        }

        return $this->cleanup();
    }

    /**
     * Fire the OnBabelUnlink event
     */
    public function fireUnlinkEvent($targetResource = null)
    {
        $this->modx->invokeEvent('OnBabelUnlink', [
            'context_key' => $this->getProperty('context_key'),
            'original_id' => $this->object->get('id'),
            'original_resource' => &$this->object,
            'target_id' => ($targetResource) ? $targetResource->get('id') : 0,
            'target_resource' => $targetResource ?: null,
        ]);
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function cleanup()
    {
        $output = $this->object->toArray();
        $output['menu'] = $this->babel->getMenu($this->object);
        return $this->success('', $output);
    }
}

return 'BabelResourceDeleteProcessor';
