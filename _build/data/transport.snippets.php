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