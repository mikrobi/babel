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
class BabelUnlinkResourceProcessor extends modObjectGetProcessor {

    public $classKey = 'modResource';
    public $languageTopics = array('resource', 'babel:default');
    public $objectType = 'resource';
    public $targetResource;

    public function initialize() {
        $target = $this->getProperty('target', false);
        if (empty($target)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        if ($target === $primaryKey) {
            return $this->modx->lexicon('error.unlink_of_selflink_not_possible');
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
    public function process() {
        $props = $this->getProperties();

        $targetResources = $this->modx->babel->getLinkedResources($props['target']);
        if (!isset($targetResources[$this->object->get('context_key')])) {
            return $this->failure($this->modx->lexicon('error.no_link_to_context', array(
                                'context' => $props['context'],
            )));
        }

        $linkedResources = $this->modx->babel->getLinkedResources($this->object->get('id'));
        if (!isset($linkedResources[$props['context']])) {
            return $this->failure($this->modx->lexicon('error.no_link_to_context', array(
                                'context' => $this->object->get('context_key'),
            )));
        }

        $this->modx->babel->initBabelTv($this->targetResource);
        unset($linkedResources[$props['context']]);
        $this->modx->babel->updateBabelTv($linkedResources, $linkedResources);

        return $this->cleanup();
    }

    /**
     * Return the response
     * @return array
     */
    public function cleanup() {
        $output = $this->object->toArray();
        $output['menu'] = $this->modx->babel->getMenu($this->object);
        return $this->success('', $output);
    }

}

return 'BabelUnlinkResourceProcessor';
