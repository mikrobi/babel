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
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package babel
 */
/**
 *  Adds events to Babel plugin
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
$events = array();

$events['OnDocFormPrerender'] = $modx->newObject('modPluginEvent');
$events['OnDocFormPrerender']->fromArray(array(
    'event' => 'OnDocFormPrerender',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

$events['OnDocFormSave'] = $modx->newObject('modPluginEvent');
$events['OnDocFormSave']->fromArray(array(
    'event' => 'OnDocFormSave',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

$events['OnEmptyTrash'] = $modx->newObject('modPluginEvent');
$events['OnEmptyTrash']->fromArray(array(
    'event' => 'OnEmptyTrash',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

$events['OnContextRemove'] = $modx->newObject('modPluginEvent');
$events['OnContextRemove']->fromArray(array(
    'event' => 'OnContextRemove',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

return $events;