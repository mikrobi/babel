<?php
/**
 * BabelLinks snippet to display links to translated resources
 * 
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * 
 * @param resourceId	optional: id of resource of which links should be displayed. Default: current resource
 * @param tpl			optional: Chunk to display a language link. Default: babelLink
 * @param activeCls		optional: CSS class name for the current active language. Default: active
 */
$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/',$scriptProperties);

if (!($babel instanceof Babel)) return;

/* be sure babel TV is loaded */
if(!$babel->babelTv) return;

/* get plugin properties */
$resourceId = intval($modx->getOption('resourceId',$scriptProperties,$modx->resource->get('id')));
$tpl = $modx->getOption('tpl',$scriptProperties,'babelLink');
$activeCls = $modx->getOption('activeCls',$scriptProperties,'active');

if($resourceId == $modx->resource->get('id')) {
	$contextKeys = $babel->getGroupContextKeys($modx->resource->get('context_key'));
} else {
	$resource = $modx->getObject('modResource', $resourceId);
	if(!$resource) {
		return;
	}
	$contextKeys = $babel->getGroupContextKeys($resource->get('context_key'));
}

$linkedResources = $babel->getLinkedResources(resourceId);

$output = '';

foreach($contextKeys as $contextKey) {
	$context = $modx->getObject('modContext', $contextKey);
	if(!$context) {
		$modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
		continue;
	}
	$context->prepare();
	$cultureKey = $context->getOption('cultureKey',$modx->getOption('cultureKey'));
	$translationAvailable = false;
	if(isset($linkedResources[$contextKey])) {
		$resource = $modx->getObject('modResource',$linkedResources[$contextKey]);
		if($resource && $resource->get('published') == 1) {
			$translationAvailable = true;
		}
	}
	if($translationAvailable) {
		$url = $context->makeUrl($linkedResources[$contextKey],'','full');
	} else {
		$url = $context->getOption('site_url', $modx->getOption('site_url'));
	}
	$active = ($modx->resource->get('context_key') == $contextKey) ? $activeCls : '';
	$placeholders = array(
		'cultureKey' => $cultureKey,
		'url' => $url,
		'active' => $active);
	$output .= $babel->getChunk($tpl,$placeholders);
}
  
return $output;