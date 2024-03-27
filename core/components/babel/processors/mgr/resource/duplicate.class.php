<?php
/**
 * Duplicate resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelResourceDuplicateProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];
    public $permission = 'resource_duplicate';

    /** @var modResource $object The resource to duplicate */
    public $object;

    /** @var modResource $newObject The newly duplicated resource */
    public $newObject;

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
        $this->newObject = $this->babel->duplicateResource($this->object, $context);
        if (!$this->newObject) {
            return $this->failure($this->modx->lexicon('babel.translation_err_could_not_create_resource', [
                'context' => $context
            ]));
        }

        $targetResources = $this->babel->getLinkedResources($this->getProperty('target'));
        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        $linkedResources[$context] = $this->newObject->get('id');

        $syncLinkedTranslations = $this->getProperty('sync');
        if ($syncLinkedTranslations == 1) {
            /* Join all existing linked resources from both resources */
            $mergedResources = array_merge($targetResources, $linkedResources);
            $this->babel->updateBabelTv($mergedResources, $mergedResources);
        } else {
            /* Only join between 2 resources */
            $mergeLinked = array_merge($linkedResources, [
                $this->getProperty('context_key') => $this->newObject->get('id')
            ]);
            $this->babel->updateBabelTv($this->object->get('id'), $mergeLinked);
            $mergeTarget = array_merge($targetResources, [
                $this->object->get('context_key') => $this->object->get('id')
            ]);
            $this->babel->updateBabelTv($this->newObject->get('id'), $mergeTarget);
        }

        $copyTvValues = $this->getProperty('copy');
        if ($copyTvValues == 1) {
            /* copy values of synchronized TVs to target resource */
            $this->babel->synchronizeTvs($this->object->get('id'));
        }

        $this->fireDuplicateEvent();
        $this->logManagerAction();

        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.translation_success_create_resource', [
            'id' => $this->newObject->get('id'),
            'context' => $context,
        ]));
        if ($this->getBooleanProperty('last')) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
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
        $output = $this->newObject->toArray();
        return $this->success('', $output);
    }
}

return 'BabelResourceDuplicateProcessor';
