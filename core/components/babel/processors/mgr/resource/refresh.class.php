<?php
/**
 * Refresh resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelResourceRefreshProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];
    public $permission = 'resource_duplicate';

    /** @var modResource $object The resource to duplicate */
    public $object;

    /** @var modResource $linkedObject The linked resource */
    public $linkedObject;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $success = parent::initialize();

        $contextKey = $this->getProperty('context_key', false);
        if (empty($contextKey)) {
            return $this->modx->lexicon('babel.context_err_ns');
        }
        $context = $this->modx->getObject('modContext', [
            'key' => $contextKey
        ]);
        if (!$context) {
            return $this->modx->lexicon('babel.context_err_invalid_key', [
                'context' => $contextKey
            ]);
        }

        return $success;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $context = $this->getProperty('context_key');
        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));

        /** @var modResource $linkedResource */
        $linkedResource = ($linkedResources[$context]) ? $this->modx->getObject($this->classKey, $linkedResources[$context]) : null;
        if ($linkedResource) {
            $this->linkedObject = $this->babel->refreshResource($this->object, $linkedResource);
        }

        if (!$this->linkedObject) {
            return $this->failure($this->modx->lexicon('babel.translation_err_could_not_sync_resource', [
                'context' => $context
            ]));
        }

        $copyTvValues = $this->getProperty('copy');
        if ($copyTvValues == 1) {
            // Copy values of synchronized TVs and resource fields to the target resource
            $this->babel->synchronizeTvs($this->object->get('id'), $this->linkedObject->get('id'));
            $this->babel->synchronizeFields($this->object->get('id'), $this->linkedObject->get('id'));
        }

        $this->fireDuplicateEvent();
        $this->logManagerAction();

        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.success_sync_resource', [
            'id' => $this->object->get('id'),
            'newid' => $this->linkedObject->get('id'),
            'context' => $context,
        ]));
        if ($this->getBooleanProperty('last')) {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'COMPLETED');
        }

        return $this->cleanup();
    }

    /**
     * Fire the OnBabelDuplicate event
     */
    public function fireDuplicateEvent()
    {
        $this->modx->invokeEvent('OnBabelDuplicate', [
            'context_key' => $this->getProperty('context_key'),
            'original_id' => $this->object->get('id'),
            'original_resource' => &$this->object,
            'duplicate_id' => $this->linkedObject->get('id'),
            'duplicate_resource' => &$this->linkedObject,
        ]);
    }

    /**
     * Log a manager action
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_duplicate', $this->classKey, $this->linkedObject->get('id'));
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function cleanup()
    {
        $output = $this->linkedObject->toArray();
        return $this->success('', $output);
    }
}

return 'BabelResourceRefreshProcessor';
