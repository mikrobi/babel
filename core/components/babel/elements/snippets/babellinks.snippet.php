<?php
/**
 * BabelLink snippet to display links to translated resources
 * 
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * 
 * @param tpl			Chunk to display a language link.
 * @param activeCls		CSS class name for the current active language.
 */
$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/',$scriptProperties);

if (!($babel instanceof Babel)) return;

/* be sure babel TV is loaded */
if(!$babel->babelTv) return;

/* get plugin properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'babelLink');
$activeCls = $modx->getOption('activeCls',$scriptProperties,'active');

$contextKeys = $babel->getGroupContextKeys($modx->resource->get('context_key'));
$babelTvValue = $babel->babelTv->getValue($modx->resource->get('id'));
$linkedResources = $babel->decodeTranslationLinks($babelTvValue);

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
