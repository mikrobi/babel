<?php
/**
 * BabelTranslation snippet to get the id of a translated resource in a given context.
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * 
 * @param resourceId	optional: id of resource of which a translated resource should be determined. Default: current resource
 * @param contextKey	Key of context in which translated resource should be determined.
 */
$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/',$scriptProperties);

if (!($babel instanceof Babel)) return;

/* be sure babel TV is loaded */
if(!$babel->babelTv) return;

/* get plugin properties */
$resourceId = $modx->resource->get('id');
if(!empty($scriptProperties['resourceId'])) {
	$resourceId = intval($modx->getOption('resourceId',$scriptProperties,$resourceId));
}
$contextKey = $modx->getOption('contextKey',$scriptProperties,'');

/* determine id of tranlated resource */
$linkedResources = $babel->getLinkedResources($resourceId);
$output = null;
if(isset($linkedResources[$contextKey])) {
	$resource = $modx->getObject('modResource',$linkedResources[$contextKey]);
	if($resource && $resource->get('published') == 1) {
		$output = $resource->get('id');
	}
}
return $output;