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
 * BabelTranslation snippet to get the id of a translated resource in a given context.
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * 
 * @param resourceId		optional: id of resource of which a translated resource should be determined. Default: current resource
 * @param contextKey		Key of context in which translated resource should be determined.
 * @param showUnpublished	optional: flag whether to show unpublished translations. Default: 0
 */
$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/',$scriptProperties);

if (!($babel instanceof Babel)) return;

/* be sure babel TV is loaded */
if(!$babel->babelTv) return;

/* get snippet properties */
$resourceId = $modx->resource->get('id');
if(!empty($scriptProperties['resourceId'])) {
	$resourceId = intval($modx->getOption('resourceId',$scriptProperties,$resourceId));
}
$contextKey = $modx->getOption('contextKey',$scriptProperties,'');
$showUnpublished = $modx->getOption('showUnpublished',$scriptProperties,0);

/* determine id of tranlated resource */
$linkedResources = $babel->getLinkedResources($resourceId);
$output = null;
if(isset($linkedResources[$contextKey])) {
	$resource = $modx->getObject('modResource',$linkedResources[$contextKey]);
	if($resource && ($showUnpublished || $resource->get('published') == 1)) {
		$output = $resource->get('id');
	}
}
return $output;