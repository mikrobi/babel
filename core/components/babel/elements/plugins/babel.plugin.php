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
 * Babel Plugin to link and synchronize multilingual resources
 * 
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * Events:
 * OnDocFormPrerender,OnDocFormSave,OnEmptyTrash,OnContextRemove,OnResourceDuplicate
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *         goldsky <goldsky@virtudraft.com>
 * 
 * @package babel
 * 
 */

$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/');

/* be sure babel TV is loaded */
if (!($babel instanceof Babel) || !$babel->babelTv) return;

switch ($modx->event->name) {
	case 'OnDocFormPrerender':
		$output = '';
		$errorMessage = '';
		$resource =& $modx->event->params['resource'];
		if(!$resource) {
			/* a new resource is being to created
			 * -> skip rendering the babel box */
			break;
		}
		$linkedResources = $babel->getLinkedResources($resource->get('id'));
		if(empty($linkedResources)) {
			/* always be sure that the Babel TV is set */
			$babel->initBabelTv($resource);
		}

		/* create babel-box with links to translations */
		$outputLanguageItems = '';
        if (!$modx->lexicon) {
            $modx->getService('lexicon','modLexicon');
        }
        $languagesStore = array();
		$contextKeys = $babel->getGroupContextKeys($resource->get('context_key'));
		foreach($contextKeys as $contextKey) {
			/* for each (valid/existing) context of the context group a button will be displayed */
			$context = $modx->getObject('modContext', array('key' => $contextKey));
			if(!$context) {
				$modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
				continue;
			}
			$context->prepare();
			$cultureKey = $context->getOption('cultureKey',$modx->getOption('cultureKey'));
            $languagesStore[] = array($modx->lexicon('babel.language_'.$cultureKey)." ($contextKey)", $contextKey);
        }
		
        $babel->config['context_key'] = $resource->get('context_key');
        $babel->config['languagesStore'] = $languagesStore;
        $babel->config['menu'] = $babel->getMenu($resource);

        $version = str_replace(' ', '', $babel->config['version']);
        $isCSSCompressed = $modx->getOption('compress_css');
        $withVersion = $isCSSCompressed ? '' : '?v='.$version;
        $modx->controller->addCss($babel->config['cssUrl'].'babel.css'.$withVersion);

        $modx->controller->addLexiconTopic('babel:default');
        $isJsCompressed = $modx->getOption('compress_js');
        $withVersion = $isJsCompressed ? '' : '?v='.$version;
        $modx->controller->addJavascript($babel->config['jsUrl'].'babel.class.js'.$withVersion);
        $modx->controller->addHtml('
<script type="text/javascript">
    Ext.onReady(function () {
        var babel = new Babel('.json_encode($babel->config).');
        babel.getMenu(babel.config.menu);
    });
</script>');
        break;
	
	case 'OnDocFormSave':
		$resource =& $modx->event->params['resource'];
		if(!$resource) {
			$modx->log(modX::LOG_LEVEL_ERROR, 'No resource provided for OnDocFormSave event');
			break;
		}
		if($modx->event->params['mode'] == modSystemEvent::MODE_NEW) {
			/* no TV synchronization for new resources, just init Babel TV */
			$babel->initBabelTv($resource);
			break;
		}
		$babel->synchronizeTvs($resource->get('id'));
		break;
		
	case 'OnEmptyTrash':
		/* remove translation links to non-existing resources */
		$deletedResourceIds =& $modx->event->params['ids'];
		if(is_array($deletedResourceIds)) {
			foreach ($deletedResourceIds as $deletedResourceId) {
				$babel->removeLanguageLinksToResource($deletedResourceId);
			}
		}		
		break;
		
	case 'OnContextRemove':
		/* remove translation links to non-existing contexts */
		$context =& $modx->event->params['context'];
		if($context) {
			$babel->removeLanguageLinksToContext($context->get('key'));
		}
		break;
	
	case 'OnResourceDuplicate':
		/* init Babel TV of duplicated resources */
		$resource =& $modx->event->params['newResource'];
        $babel->initBabelTvsRecursive($modx,$babel,$resource->get('id')); 
		break;
}
return;