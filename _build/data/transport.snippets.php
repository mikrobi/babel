<?php
/**
 * Add snippets to build
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
$snippets = array();

$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'BabelLinks',
    'description' => 'Displays links to translated resources.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/babellinks.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.babellinks.php';
$snippets[0]->setProperties($properties);
unset($properties);

return $snippets;