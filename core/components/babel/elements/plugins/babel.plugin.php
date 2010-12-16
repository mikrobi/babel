<?php
/**
 * Babel Plugin to link and synchronize multilingual resources
 * 
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * Events: OnDocFormPrerender,OnDocFormSave,OnEmptyTrash
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * 
 */

$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/',$scriptProperties);

if (!($babel instanceof Babel)) return;

/* be sure babel TV is loaded */
if(!$babel->babelTv) return;

switch ($modx->event->name) {
	case 'OnDocFormPrerender':
		$resource =& $modx->event->params['resource'];
		if(!$resource) {
			/* a new resource is being to created
			 * -> skip rendering the babel box */
			break;
		}
		$contextKeys = $babel->getGroupContextKeys($resource->get('context_key'));
		$babelTvValue = $babel->babelTv->getValue($resource->get('id'));
		$linkedResources = $babel->decodeTranslationLinks($babelTvValue);
		
		switch($_GET['babel_action']) {
			case 'translate':
				/* create transalation for the current resource (if not present) */
				if (!empty($linkedResources)) {
					/* translations have already been created */
					break;
				}

				$currentContextKey = $resource->get('context_key');	 
				$linkedResources[$currentContextKey] = $resource->get('id');
				foreach($contextKeys as $contextKey){
					if($currentContextKey != $contextKey) {
						/* check if context is valid */
						$context = $modx->getObject('modContext', array('key' => $contextKey));
						if(!$context) {
							$modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
							continue;
						}
						$newResource = $babel->duplicateResource($resource, $contextKey);
						if($newResource) {										
							$linkedResources[$contextKey] = $newResource->get('id');
						}
					} else {
						$linkedResources[$contextKey] = $resource->get('id');
					}
				}
				
				/* update babel TV for above linked resources */
				$linkedResourcesString = $babel->encodeTranslationLinks($linkedResources);
				foreach($linkedResources as $resourceId){
					$babel->babelTv->setValue($resourceId,$linkedResourcesString);
				}
				$babel->babelTv->save();
				$modx->cacheManager->clearCache();
				
				break;
		}
		
		/* grab actions */
		$actions = $modx->request->getAllActionIDs();
		
		$output = '';
		if (empty($linkedResources)) {
			/* resource has not been linked to translated resources yet:
			 * -> show button to create tranlations */
			$output = '<a href="?a='.$actions['resource/update'].'&amp;id='.$resource->get('id').'&amp;babel_action=translate">'.$modx->lexicon('babel.create_translations').'</a>';
		} else {
			/* resource is linkted to translated resource:
			 * -> show links to switch between these resources */	
			foreach($linkedResources as $contextKey => $resourceId){
				$context = $modx->getObject('modContext', array('key' => $contextKey));
				if(!$context) {
					$modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
					continue;
				}
				$context->prepare();
				$cultureKey = $context->getOption('cultureKey',$modx->getOption('cultureKey'));
				
				$class = ($resourceId == $resource->get('id')) ?  ' class="selected"' : '';
				$output .= '<a href="?a='.$actions['resource/update'].'&amp;id='.$resourceId.'"'.$class.'>'.$modx->lexicon('babel.language_'.$cultureKey).' ('.$contextKey.')</a>';
			}
		}
		$output = '<div id="babelbox">'.$output.'</div>';
		$modx->event->output($output);
		
		/* include CSS */
		$modx->regClientCSS($babel->config['cssUrl'].'babel.css');
		break;
	
	case 'OnDocFormSave':
		/* synchronize the specified TVs of linked resources */
		$syncTvs = $babel->config['syncTvs'];
		if(empty($syncTvs) || !is_array($syncTvs)) break;
		
		$resource =& $modx->event->params['resource'];
		$babelTvValue = $babel->babelTv->getValue($resource->get('id'));
		$linkedResources = $babel->decodeTranslationLinks($babelTvValue);
		
		foreach($linkedResources as $resourceId){
			/* go through each linked resource */
			foreach($syncTvs as $tvId){
				/* go through each TV which should be synchronized */
				$tv = $modx->getObject('modTemplateVar',$tvId);
				$tvValue = $tv->getValue($resource->get('id'));
				$tv->setValue($resourceId, $tvValue);
				$tv->save();
			}
		}
		$modx->cacheManager->clearCache();
		
		break;
	case 'OnEmptyTrash':
		/* remove translation links to non-existing resources */
		$deletedResourceIds = $modx->event->params['ids'];
		if(!is_array($deletedResourceIds)) {
			break;
		}
		
		foreach ($deletedResourceIds as $deletedResourceId) {
			$babel->removeLanguageLinksToResource($deletedResourceId);
		}
		break;
}
return;
