<?php
/**
 * Babel
 *
 * Copyright 2010 by Jakob Class <jakob.class@class-zec.de>
 *
 * This file is part of Babel.
 *
 * Babel is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Babel is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Babel; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package babel
 */

/**
 * Processor file for Babel.
 *
 * @author goldsky <goldsky@virtudraft.com>
 *
 * @package babel
 */
class BabelLinkResourceProcessor extends modObjectGetProcessor
{

    public $classKey       = 'modResource';
    public $languageTopics = array('resource', 'babel:default');
    public $objectType     = 'resource';
    public $targetResource;

    public function initialize()
    {
        $target = $this->getProperty('target', false);
        if (empty($target)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        if ($target === $primaryKey) {
            return $this->modx->lexicon('error.link_of_selflink_not_possible');
        }

        $this->targetResource = $this->modx->getObject('modResource', intval($target));
        if (!$this->targetResource) {
            return $this->modx->lexicon('error.invalid_resource_id', array('resource' => $target));
        }

        $contextKey = $this->getProperty('context', false);
        if (empty($contextKey)) {
            return $this->modx->lexicon('babel.context_err_ns');
        }

        $context = $this->modx->getObject('modContext', array('key' => $contextKey));
        if (!$context) {
            return $this->modx->lexicon('error.invalid_context_key', array('context' => $contextKey));
        }

        if ($this->targetResource->get('context_key') !== $contextKey) {
            return $this->modx->lexicon('error.resource_from_other_context', array(
                    'resource' => $this->targetResource->get('id'),
                    'context' => $contextKey
            ));
        }

        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $props = $this->getProperties();

        $targetResources = $this->modx->babel->getLinkedResources($props['target']);
//        if (count($targetResources) > 1 && isset($targetResources[$this->object->get('context_key')])) {
//            return $this->failure($this->modx->lexicon('error.translation_already_exists', array(
//                                'context' => $props['context'],
//                                'resource' => $targetResources[$props['context']],
//                                'pagetitle' => $this->modx->getObject('modResource', $targetResources[$props['context']])->get('pagetitle'),
//            )));
//        }

        $linkedResources = $this->modx->babel->getLinkedResources($this->object->get('id'));
        if (empty($linkedResources)) {
            /* always be sure that the Babel TV is set */
            $this->modx->babel->initBabelTv($this->object);
        }

        /* add or change a translation link */
        if (isset($linkedResources[$props['context']])) {
            /* existing link has been changed:
             * -> reset Babel TV of old resource */
            $this->modx->babel->initBabelTvById($linkedResources[$props['context']]);
        }
        $linkedResources[$props['context']] = $this->targetResource->get('id');

        if (isset($props['sync-linked-tranlations']) && intval($props['sync-linked-tranlations']) == 1) {
            /**
             * Join all existing linked resources from both resources
             */
            $mergedResources = array_merge($targetResources, $linkedResources);
            $this->modx->babel->updateBabelTv($mergedResources, $mergedResources);
        } else {
            /**
             * Only join between 2 resources
             */
            $merge1 = array_merge($linkedResources, array(
                $props['context'] => $this->targetResource->get('id')
            ));
            $this->modx->babel->updateBabelTv($this->object->get('id'), $merge1);
            $merge2 = array_merge($targetResources, array(
                $this->object->get('context_key') => $this->object->get('id')
            ));
            $this->modx->babel->updateBabelTv($this->targetResource->get('id'), $merge2);
        }

        /* copy values of synchronized TVs to target resource */
        if (isset($props['copy-tv-values']) && intval($props['copy-tv-values']) == 1) {
            $this->modx->babel->synchronizeTvs($this->object->get('id'));
        }

        $this->fireLinkEvent();
        return $this->cleanup();
    }

    /**
     * Fire the OnBabelLink event
     * @return void
     */
    public function fireLinkEvent()
    {
        $this->modx->invokeEvent('OnBabelLink', array(
            'context_key'       => $this->getProperty('context'),
            'original_id'       => $this->object->get('id'),
            'original_resource' => &$this->object,
            'target_id'         => $this->targetResource->get('id'),
            'target_resource'   => &$this->targetResource
        ));
    }

    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        $output = $this->object->toArray();
        $output['menu'] = $this->modx->babel->getMenu($this->object);
        return $this->success('', $output);
    }
}

return 'BabelLinkResourceProcessor';
