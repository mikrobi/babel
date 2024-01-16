<?php
/**
 * Duplicate resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelDuplicateResourceProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];
    public $permission = 'resource_duplicate';

    /** @var xPDOObject $newObject The newly duplicated object */
    protected $newObject;

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
        $contextKey = $this->getProperty('context_key');
        $this->newObject = $this->babel->duplicateResource($this->object, $contextKey);
        if (!$this->newObject) {
            return $this->failure($this->modx->lexicon('babel.translation_err_could_not_create_resource', [
                'context' => $contextKey
            ]));
        }

        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        $linkedResources[$contextKey] = $this->newObject->get('id');
        $this->babel->updateBabelTv($linkedResources, $linkedResources);

        $this->fireDuplicateEvent();
        $this->logManagerAction();
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
            'duplicate_id' => $this->newObject->get('id'),
            'duplicate_resource' => &$this->newObject,
        ]);
    }

    /**
     * Log a manager action
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_duplicate', $this->classKey, $this->newObject->get('id'));
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->newObject);
    }
}

return 'BabelDuplicateResourceProcessor';
