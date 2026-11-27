<?php
/**
 * Unink resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelResourceUnlinkProcessor extends ObjectUpdateProcessor
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
            if (!$this->babel->canUpdateAny(array_merge([$this->object->get('id')], array_values($linkedResources)))) {
                return $this->failure($this->modx->lexicon('permission_denied'));
            }
            // Unlink this resource from all resources
            foreach ($linkedResources as $linkedResource) {
                $diff = array_diff($this->babel->getLinkedResources($linkedResource), [
                    $this->object->get('context_key') => $this->object->get('id')
                ]);
                $this->babel->updateAllowedBabelTv($linkedResource, $diff);
            }
            $this->babel->updateAllowedBabelTv($this->object->get('id'), []);
            $this->fireUnlinkEvent();
            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.success_unlink_resources', [
                'id' => $this->object->get('id'),
            ]));
        } else {
            if (!isset($linkedResources[$contextKey])) {
                return $this->failure($this->modx->lexicon('babel.context_err_invalid_key', [
                    'context' => $contextKey
                ]));
            }
            $target = $linkedResources[$contextKey];
            /** @var modResource $targetResource */
            $targetResource = $this->modx->getObject('modResource', $target);
            if (!$targetResource) {
                return $this->failure($this->modx->lexicon('babel.resource_err_invalid_id', [
                    'resource' => $target
                ]));
            }
            // One side of the link has to be editable
            if (!$this->babel->canUpdateAny([$this->object->get('id'), $target])) {
                return $this->failure($this->modx->lexicon('permission_denied'));
            }
            $this->babel->removeTranslation($targetResource, $linkedResources);
            $this->fireUnlinkEvent($targetResource);
            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.success_unlink_resource', [
                'id' => $this->object->get('id'),
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

return 'BabelResourceUnlinkProcessor';
