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

$snippets[1] = $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 0,
    'name' => 'BabelTranslation',
    'description' => 'Returns the id of a translated resource in a given context.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/babeltranslation.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.babeltranslation.php';
$snippets[1]->setProperties($properties);
unset($properties);

return $snippets;