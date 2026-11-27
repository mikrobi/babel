<?php
/**
 * Link resource
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectUpdateProcessor;

class BabelResourceLinkProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'modResource';
    public $objectType = 'resource';
    public $languageTopics = ['resource', 'babel:default'];

    /** @var modResource $object The link source */
    public $object;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $success = parent::initialize();

        $target = $this->getProperty('target', false);
        if (empty($target)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if ((int)$target === (int)$primaryKey) {
            return $this->modx->lexicon('babel.resource_err_link_of_selflink_not_possible');
        }
        $targetResource = $this->modx->getObject('modResource', $target);
        if (!$targetResource) {
            return $this->modx->lexicon('babel.resource_err_invalid_id', [
                'resource' => $target
            ]);
        }
        $contextKey = $this->getProperty('context', false);
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
        if ($targetResource->get('context_key') !== $contextKey) {
            return $this->modx->lexicon('babel.resource_err_from_other_context', [
                'resource' => $target,
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
        $target = $this->getProperty('target', false);
        $targetResource = $this->modx->getObject('modResource', $target);
        $targetResources = $this->babel->getLinkedResources($target);
        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        if (empty($linkedResources)) {
            // Always be sure that the Babel TV is set
            $linkedResources = $this->babel->initBabelTv($this->object);
        }

        $context = $this->getProperty('context');
        // One side of the new link has to be editable
        if (!$this->babel->canUpdateAny([$this->object->get('id'), $target])) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        // Copying the values updates all linked resources, so check the permissions before anything is changed
        if ($this->getProperty('copy') == 1) {
            $writtenResources = array_merge(
                ($this->getProperty('sync') == 1) ? $targetResources : [],
                $linkedResources,
                [$context => $target]
            );
            foreach ($writtenResources as $resourceId) {
                /** @var modResource $writtenResource */
                $writtenResource = $this->modx->getObject('modResource', $resourceId);
                if ($writtenResource && (int)$resourceId !== (int)$this->object->get('id') && !$this->babel->canUpdate($writtenResource)) {
                    return $this->failure($this->modx->lexicon('permission_denied'));
                }
            }
        }
        // Add or change a translation link
        $oldId = (int)($linkedResources[$context] ?? 0);
        if ($oldId && $oldId !== (int)$target && $oldId !== (int)$this->object->get('id')) {
            // If the existing link is changed, remove the old resource from the translation group
            $oldResource = $this->modx->getObject('modResource', $oldId);
            if ($oldResource) {
                $this->babel->removeTranslation($oldResource, $linkedResources);
                $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
                $targetResources = $this->babel->getLinkedResources($target);
            }
        }
        $linkedResources[$context] = $target;

        $syncLinkedTranslations = $this->getProperty('sync');
        if ($syncLinkedTranslations == 1) {
            // Join all existing linked resources from both resources
            $mergedResources = array_merge($targetResources, $linkedResources);
            $this->babel->updateAllowedBabelTv($mergedResources, $mergedResources);
        } else {
            // Only join between 2 resources
            $mergeLinked = array_merge($linkedResources, [
                $this->getProperty('context') => $target
            ]);
            $this->babel->updateAllowedBabelTv($this->object->get('id'), $mergeLinked);
            $mergeTarget = array_merge($targetResources, [
                $this->object->get('context_key') => $this->object->get('id'),
                $context => $target,
            ]);
            $this->babel->updateAllowedBabelTv($target, $mergeTarget);
        }

        $copyTvValues = $this->getProperty('copy');
        if ($copyTvValues == 1) {
            // Copy values of synchronized TVs and resource fields to the target resource
            $this->babel->synchronizeTvs($this->object->get('id'));
            $this->babel->synchronizeFields($this->object->get('id'));
        }

        $this->fireLinkEvent($targetResource);
        return $this->cleanup();
    }

    /**
     * Fire the OnBabelLink event
     */
    public function fireLinkEvent($targetResource = null)
    {
        $this->modx->invokeEvent('OnBabelLink', [
            'context_key' => $this->getProperty('context'),
            'original_id' => $this->object->get('id'),
            'original_resource' => &$this->object,
            'target_id' => $targetResource->get('id'),
            'target_resource' => &$targetResource
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

return 'BabelResourceLinkProcessor';
