<?php
/**
 * Unink resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelUnlinkResourceProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];

    /** @var modResource $targetResource The link target */
    protected $targetResource;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $success = parent::initialize();

        $target = $this->getProperty('target', false);
        $contextKey = $this->getProperty('context', false);
        if (empty($target) && !empty($contextKey)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if ($target === $primaryKey) {
            return $this->modx->lexicon('babel.resource_err_unlink_of_selflink_not_possible');
        }

        if ($target) {
            if (empty($contextKey)) {
                return $this->modx->lexicon('babel.context_err_ns');
            }
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
        $targetResources = $this->babel->getLinkedResources($this->getProperty('target'));
        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        if (empty($linkedResources)) {
            /* always be sure that the Babel TV is set */
            $this->babel->initBabelTv($this->object);
        }

        $target = $this->getProperty('target');
        if (empty($target)) {
            /* Unlink this resource from all resources */
            foreach ($linkedResources as $v) {
                $resources = $this->babel->getLinkedResources($v);
                $diff = array_diff($resources, [
                    $this->object->get('context_key') => $this->object->get('id')
                ]);
                $this->babel->updateBabelTv($v, $diff);
            }
            $this->babel->updateBabelTv($this->object->get('id'), []);

            return $this->cleanup();
        }

        $this->targetResource = $this->modx->getObject('modResource', $target);
        if (!$this->targetResource) {
            return $this->failure($this->modx->lexicon('babel.resource_err_invalid_id', [
                'resource' => $target
            ]));
        }

        if (empty($targetResources)) {
            $this->babel->initBabelTv($this->targetResource);
        }
        unset($targetResources[$this->object->get('context_key')]);
        $this->babel->updateBabelTv($targetResources, $targetResources);
        unset($linkedResources[$this->getProperty('context')]);
        $this->babel->updateBabelTv($this->object->get('id'), $linkedResources);

        $this->fireUnlinkEvent();
        return $this->cleanup();
    }

    /**
     * Fire the OnBabelUnlink event
     */
    public function fireUnlinkEvent()
    {
        $this->modx->invokeEvent('OnBabelUnlink', [
            'context_key' => $this->getProperty('context'),
            'original_id' => $this->object->get('id'),
            'original_resource' => &$this->object,
            'target_id' => $this->targetResource->get('id'),
            'target_resource' => &$this->targetResource
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

return 'BabelUnlinkResourceProcessor';
