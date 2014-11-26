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
class BabelDuplicateResourceProcessor extends modObjectProcessor {

    public $classKey = 'modResource';
    public $languageTopics = array('resource', 'babel:default');
    public $objectType = 'resource';

    /** @var xPDOObject $newObject The newly duplicated object */
    public $newObject;

    public function initialize() {
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, $primaryKey);
        if (empty($this->object)) {
            return $this->modx->lexicon($this->objectType . '_err_nfs', array($this->primaryKeyField => $primaryKey));
        }

        if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        $contextKey = $this->getProperty('context_key', false);
        if (empty($contextKey)) {
            return $this->modx->lexicon('babel.context_err_ns');
        }

        $context = $this->modx->getObject('modContext', array('key' => $contextKey));
        if (!$context) {
            return $this->modx->lexicon('error.invalid_context_key', array('context' => $contextKey));
        }

        return true;
    }

    public function process() {
        $contextKey = $this->getProperty('context_key');
        $this->newObject = $this->modx->babel->duplicateResource($this->object, $contextKey);
        if (!$this->newObject) {
            /* error: translation could not be created */
            return $this->failure($this->modx->lexicon('error.could_not_create_translation', array('context' => $contextKey)));
        }

        $linkedResources = $this->modx->babel->getLinkedResources($this->object->get('id'));
        $linkedResources[$contextKey] = $this->newObject->get('id');
        $this->modx->babel->updateBabelTv($linkedResources, $linkedResources);

        $this->logManagerAction();
        return $this->cleanup();
    }

    /**
     * Log a manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction($this->objectType . '_duplicate', $this->classKey, $this->newObject->get('id'));
    }

    /**
     * Cleanup and return a response.
     *
     * @return array
     */
    public function cleanup() {
        return $this->success('', $this->newObject);
    }

}

return 'BabelDuplicateResourceProcessor';
