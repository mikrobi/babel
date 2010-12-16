<?php
/**
 * Add plugins to build
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
$plugins = array();

$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','Babel');
$plugins[0]->set('description','Links and synchronizes multilingual resources.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'babel.plugin.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'].'events.babel.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for Babel.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for Babel!');
}
unset($events);

return $plugins;