<?php
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