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
if (!class_exists('\MODX\Revolution\modX')) {
    include_once MODX_CORE_PATH.'model/modx/processors/resource/getlist.class.php';
} else {
    class_alias(\MODX\Revolution\Processors\Resource\GetList::class, \modResourceGetListProcessor::class);
}

class BabelResourceGetMatrixListProcessor extends modResourceGetListProcessor
{
    /** @var Babel $babel */
    public $babel;

    public $defaultSortField = 'id';
    private $_contexts       = [];

    function __construct(modX & $modx,array $properties = array())
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('babel.core_path', null, $this->modx->getOption('core_path') . 'components/babel/');
        $this->babel = $this->modx->getService('babel', 'Babel', $corePath . 'model/babel/');
    }

    public function initialize()
    {
        $this->_contexts = array_map('trim', @explode(',', $this->getProperty('contexts')));

        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'pagetitle:LIKE' => '%' . $query . '%'
            ]);
        }
        $ctx = $this->getProperty('context');
        if (!empty($ctx)) {
            $c->where([
              'context_key:=' => $ctx
            ]);
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        // 'id' conflicts with Indonesian's ISO code 'id'

        $linkedResources = $this->babel->getLinkedResources($objectArray['id']);
        foreach ($this->_contexts as $ctx) {
            // 'id' conflicts with Indonesian's ISO code 'id'
            // prepend with a suffix
            $objectArray['linkedres_id_'.$ctx]        = '';
            $objectArray['linkedres_pagetitle_'.$ctx] = '';
            if ($objectArray['context_key'] === $ctx) {
                $objectArray['linkedres_id_'.$ctx]        = 'x';
                $objectArray['linkedres_pagetitle_'.$ctx] = 'x';
            } else {
                if (isset($linkedResources[$ctx]) && !empty($linkedResources[$ctx])) {
                    $objectArray['linkedres_id_'.$ctx] = $linkedResources[$ctx];
                    $resource                            = $this->modx->getObject('modResource', $linkedResources[$ctx]);
                    if ($resource) {
                        $objectArray['linkedres_pagetitle_'.$ctx] = $resource->get('pagetitle');
                    }
                }
            }
        }

        return $objectArray;
    }

}

return 'BabelResourceGetMatrixListProcessor';
