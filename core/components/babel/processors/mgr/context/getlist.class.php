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
include_once MODX_CORE_PATH.'model/modx/processors/context/getlist.class.php';

class BabelContextGetListProcessor extends modContextGetListProcessor
{

    public function initialize()
    {
        $initialized     = parent::initialize();
        $this->setDefaultProperties(array(
            'search'  => '',
            'exclude' => 'mgr',
        ));
        $this->canEdit   = false;
        $this->canRemove = false;
        return $initialized;
    }

    public function beforeIteration(array $list)
    {
        if ($this->getProperty('combo', false)) {
            $empty  = array(
                'key'  => '',
                'name' => '&nbsp;',
            );
            $list[] = $empty;
        }

        return $list;
    }

    public function prepareRow(xPDOObject $object)
    {
        $objectArray = parent::prepareRow($object);
        if ($this->getProperty('combo', false)) {
            $objectArray = array(
                'key'  => $objectArray['key'],
                'name' => $objectArray['name'],
            );
        }

        return $objectArray;
    }

}

return 'BabelContextGetListProcessor';
