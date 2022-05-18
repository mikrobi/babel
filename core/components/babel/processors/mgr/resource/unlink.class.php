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
class BabelUnlinkResourceProcessor extends modObjectGetProcessor
{
    /** @var Babel $babel */
    public $babel;

    public $classKey       = 'modResource';
    public $languageTopics = ['resource', 'babel:default'];
    public $objectType     = 'resource';
    public $targetResource;

    /** @var modAccessibleObject|xPDOObject|modResource $object The object */
    public $object;

    function __construct(modX & $modx,array $properties = array())
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('babel.core_path', null, $this->modx->getOption('core_path') . 'components/babel/');
        $this->babel = $this->modx->getService('babel', 'Babel', $corePath . 'model/babel/');
    }

    public function initialize()
    {
        $target = $this->getProperty('target', false);
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        if ($target === $primaryKey) {
            return $this->modx->lexicon('error.unlink_of_selflink_not_possible');
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

        $linkedResources = $this->babel->getLinkedResources($this->object->get('id'));
        if (empty($linkedResources)) {
            $this->babel->initBabelTv($this->object);
        }

        /**
         * Unlinked this resource to all resources
         */
        if (empty($props['target'])) {
            foreach ($linkedResources as $k => $v) {
                $targetResources = $this->babel->getLinkedResources($v);
                $diff = array_diff($targetResources, [
                    $this->object->get('context_key') => $this->object->get('id')
                ]);
                $this->babel->updateBabelTv($v, $diff);
            }

            $this->babel->updateBabelTv($this->object->get('id'), []);

            return $this->cleanup();
        }

        $target = $this->getProperty('target', false);
        $this->targetResource = $this->modx->getObject('modResource', intval($target));
        if (!$this->targetResource) {
            return $this->failure($this->modx->lexicon('error.invalid_resource_id', ['resource' => $target]));
        }

        $contextKey = $this->getProperty('context', false);
        if (empty($contextKey)) {
            return $this->failure($this->modx->lexicon('babel.context_err_ns'));
        }

        $context = $this->modx->getObject('modContext', ['key' => $contextKey]);
        if (!$context) {
            return $this->failure($this->modx->lexicon('error.invalid_context_key', ['context' => $contextKey]));
        }

        $targetResources = $this->babel->getLinkedResources($props['target']);
        if (empty($targetResources)) {
            $this->babel->initBabelTv($this->targetResource);
        }
        unset($targetResources[$this->object->get('context_key')]);
        $this->babel->updateBabelTv($targetResources, $targetResources);

        unset($linkedResources[$props['context']]);
        $this->babel->updateBabelTv($this->object->get('id'), $linkedResources);

        $this->fireUnlinkEvent();
        return $this->cleanup();
    }

    /**
     * Fire the OnBabelUnlink event
     * @return void
     */
    public function fireUnlinkEvent()
    {
        $this->modx->invokeEvent('OnBabelUnlink', [
            'context_key'       => $this->getProperty('context'),
            'original_id'       => $this->object->get('id'),
            'original_resource' => &$this->object,
            'target_id'         => $this->targetResource->get('id'),
            'target_resource'   => &$this->targetResource
        ]);
    }

    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        $output         = $this->object->toArray();
        $output['menu'] = $this->babel->getMenu($this->object);
        return $this->success('', $output);
    }

}

return 'BabelUnlinkResourceProcessor';
