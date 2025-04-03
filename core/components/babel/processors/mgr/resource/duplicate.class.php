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

    /** @var modResource $duplicatedObject The newly duplicated resource */
    public $duplicatedObject;

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
        $this->duplicatedObject = $this->babel->duplicateResource($this->object, $context);
        if (!$this->duplicatedObject) {
            return $this->failure($this->modx->lexicon('babel.translation_err_could_not_duplicate_resource', [
                'context' => $context
            ]));
        }

        $targetResources = $this->babel->getLinkedResources($this->getProperty('target'));
        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        $linkedResources[$context] = $this->duplicatedObject->get('id');

        $syncLinkedTranslations = $this->getProperty('sync');
        if ($syncLinkedTranslations == 1) {
            // Join all existing linked resources from both resources
            $mergedResources = array_merge($targetResources, $linkedResources);
            $this->babel->updateBabelTv($mergedResources, $mergedResources);
        } else {
            // Only join between 2 resources
            $mergeLinked = array_merge($linkedResources, [
                $this->getProperty('context_key') => $this->duplicatedObject->get('id')
            ]);
            $this->babel->updateBabelTv($this->object->get('id'), $mergeLinked);
            $mergeTarget = array_merge($targetResources, [
                $this->object->get('context_key') => $this->object->get('id')
            ]);
            $this->babel->updateBabelTv($this->duplicatedObject->get('id'), $mergeTarget);
        }

        $copyTvValues = $this->getProperty('copy');
        if ($copyTvValues == 1) {
            // Copy values of synchronized TVs and resource fields to the target resource
            $this->babel->synchronizeTvs($this->object->get('id'), $this->duplicatedObject->get('id'));
            $this->babel->synchronizeFields($this->object->get('id'), $this->duplicatedObject->get('id'));
        }

        $this->fireDuplicateEvent();
        $this->logManagerAction();

        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('babel.success_duplicate_resource', [
            'id' => $this->object->get('id'),
            'newid' => $this->duplicatedObject->get('id'),
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
            'duplicate_id' => $this->duplicatedObject->get('id'),
            'duplicate_resource' => &$this->duplicatedObject,
        ]);
    }

    /**
     * Log a manager action
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_duplicate', $this->classKey, $this->duplicatedObject->get('id'));
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function cleanup()
    {
        $output = $this->duplicatedObject->toArray();
        return $this->success('', $output);
    }
}

return 'BabelResourceDuplicateProcessor';
