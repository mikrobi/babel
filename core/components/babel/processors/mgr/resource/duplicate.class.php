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
class BabelDuplicateResourceProcessor extends modObjectProcessor
{
    /** @var Babel $babel */
    public $babel;

    public $classKey       = 'modResource';
    public $languageTopics = ['resource', 'babel:default'];
    public $objectType     = 'resource';

    /** @var modAccessibleObject|xPDOObject|modResource $object The object */
    public $object;
    /** @var xPDOObject $newObject The newly duplicated object */
    public $newObject;

    function __construct(modX & $modx,array $properties = array())
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('babel.core_path', null, $this->modx->getOption('core_path') . 'components/babel/');
        $this->babel = $this->modx->getService('babel', 'Babel', $corePath . 'model/babel/');
    }

    public function checkPermissions()
    {
        return $this->modx->hasPermission('resource_duplicate');
    }

    public function initialize()
    {
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, $primaryKey);
        if (empty($this->object)) {
            return $this->modx->lexicon($this->objectType.'_err_nfs', [$this->primaryKeyField => $primaryKey]);
        }

        if ($this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        $contextKey = $this->getProperty('context_key', false);
        if (empty($contextKey)) {
            return $this->modx->lexicon('babel.context_err_ns');
        }

        $context = $this->modx->getObject('modContext', ['key' => $contextKey]);
        if (!$context) {
            return $this->modx->lexicon('error.invalid_context_key', ['context' => $contextKey]);
        }

        return true;
    }

    public function process()
    {
        $contextKey      = $this->getProperty('context_key');
        $this->newObject = $this->babel->duplicateResource($this->object, $contextKey);
        if (!$this->newObject) {
            /* error: translation could not be created */
            return $this->failure($this->modx->lexicon('error.could_not_create_translation', ['context' => $contextKey]));
        }

        $linkedResources              = $this->babel->getLinkedResources($this->object->get('id'));
        $linkedResources[$contextKey] = $this->newObject->get('id');
        $this->babel->updateBabelTv($linkedResources, $linkedResources);

        $this->fireDuplicateEvent();
        $this->logManagerAction();
        return $this->cleanup();
    }

    /**
     * Fire the OnBabelDuplicate event
     * @return void
     */
    public function fireDuplicateEvent()
    {
        $this->modx->invokeEvent('OnBabelDuplicate', [
            'context_key'        => $this->getProperty('context_key'),
            'original_id'        => $this->object->get('id'),
            'original_resource'  => &$this->object,
            'duplicate_id'       => $this->newObject->get('id'),
            'duplicate_resource' => &$this->newObject,
        ]);
    }

    /**
     * Log a manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType.'_duplicate', $this->classKey, $this->newObject->get('id'));
    }

    /**
     * Cleanup and return a response.
     *
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->newObject);
    }

}

return 'BabelDuplicateResourceProcessor';
