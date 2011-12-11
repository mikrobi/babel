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
		$output = '';
		$errorMessage = '';
		$resource =& $modx->event->params['resource'];
		if(!$resource) {
			/* a new resource is being to created
			 * -> skip rendering the babel box */
			break;
		}
		$contextKeys = $babel->getGroupContextKeys($resource->get('context_key'));
		$currentContextKey = $resource->get('context_key');
		$linkedResources = $babel->getLinkedResources($resource->get('id'));
		if(empty($linkedResources)) {
			/* always be sure that the Babel TV is set */
			$babel->initBabelTv($resource);
		}
		
		/* grab manager actions IDs */
		$actions = $modx->request->getAllActionIDs();
		
		if(isset($_POST['babel-context-key'])) {
			/* one of the following babel actions has been performed: link, unlink or translate */
			try {
				$contextKey = $_POST['babel-context-key'];
				/* check if context is valid */
				$context = $modx->getObject('modContext', array('key' => $contextKey));
				if(!$context) {
					$errorParameter = array('context' => $contextKey);
					throw new Exception('error.invalid_context_key');
				}
				
				/* manuallly add or change a translation link */
				if(isset($_POST['babel-link'])) {
					if($linkedResources[$contextKey] == $_POST['babel-link-target']) {
						/* target resource is equal to current resource -> nothing to do */
						throw new Exception();
					}
					$targetResource = $modx->getObject('modResource', intval($_POST['babel-link-target']));
					if(!$targetResource) {
						/* error: resource id is not valid */
						$errorParameter = array('resource' => htmlentities($_POST['babel-link-target']));
						throw new Exception('error.invalid_resource_id');
					}
					if($targetResource->get('context_key') != $contextKey) {
						/* error: resource id of another context has been provided */
						$errorParameter = array(
							'resource' => $targetResource->get('id'),
							'context' => $contextKey);
						throw new Exception('error.resource_from_other_context');
					}
					$targetLinkedResources = $babel->getLinkedResources($targetResource->get('id'));
					if(count($targetLinkedResources) > 1) {
						/* error: target resource is already linked with other resources */
						$errorParameter = array('resource' => $targetResource->get('id'));
						throw new Exception('error.resource_already_linked');
					}
					/* add or change a translation link */
					if(isset($linkedResources[$contextKey])) {
						/* existing link has been changed:
						 * -> reset Babel TV of old resource */
						$babel->initBabelTvById($linkedResources[$contextKey]);
					}
					
					$linkedResources[$contextKey] = $targetResource->get('id');
					$babel->updateBabelTv($linkedResources, $linkedResources);
					
					/* copy values of synchronized TVs to target resource */
					if(isset($_POST['babel-link-copy-tvs']) && intval($_POST['babel-link-copy-tvs']) == 1) {
						$babel->sychronizeTvs($resource->get('id'));
					}
				}
				
				/* remove an existing translation link */
				if(isset($_POST['babel-unlink'])) {
					if(!isset($linkedResources[$contextKey])) {
						/* error: there is no link for this context */
						$errorParameter = array('context' => $contextKey);
						throw new Exception('error.no_link_to_context');
					}
					if($linkedResources[$contextKey] == $resource->get('id')) {
						/* error: (current) resource can not be unlinked from it's translations */
						$errorParameter = array('context' => $contextKey);
						throw new Exception('error.unlink_of_selflink_not_possible');
					}					
					$unlinkedResource = $modx->getObject('modResource', intval($linkedResources[$contextKey]));
					if(!$unlinkedResource) {
						/* error: invalid resource id */
						$errorParameter = array('resource' => htmlentities($linkedResources[$contextKey]));
						throw new Exception('error.invalid_resource_id');
					}
					if($unlinkedResource->get('context_key') != $contextKey) {
						/* error: resource is of a another context */
						$errorParameter = array(
							'resource' => $targetResource->get('id'),
							'context' => $contextKey);
						throw new Exception('error.resource_from_other_context');
					}
					/* unlink resource and reset its Babel TV */
					$babel->initBabelTv($unlinkedResource);
					unset($linkedResources[$contextKey]);
					$babel->updateBabelTv($linkedResources, $linkedResources);
						
				}
				
				/* create an new resource an add a translation link */
				if(isset($_POST['babel-translate'])) {
					if($currentContextKey == $contextKey) {
						/* error: translation should be created in the same context */
						throw new Exception('error.translation_in_same_context');
					}
					if(isset($linkedResources[$contextKey])) {
						/* error: there does already exist a translation */
						$errorParameter = array('context' => $contextKey);
						throw new Exception('error.translation_already_exists');
					}
										
					$newResource = $babel->duplicateResource($resource, $contextKey);
					if($newResource) {										
						$linkedResources[$contextKey] = $newResource->get('id');
						$babel->updateBabelTv($linkedResources, $linkedResources);
					} else {
						/* error: translation could not be created */
						$errorParameter = array('context' => $contextKey);
						throw new Exception('error.could_not_create_translation');
					}
					/* redirect to new resource */
					$url = $modx->getOption('manager_url',null,MODX_MANAGER_URL).'?a='.$actions['resource/update'].'&id='.$newResource->get('id');
					$modx->sendRedirect(rtrim($url,'/'),'','','full');
				}
			} catch (Exception $exception) {
				$errorKey = $exception->getMessage();
				if($errorKey) {
					if(!is_array($errorParameter)) {
						$errorParameter = array();
					}
					$errorMessage = '<div id="babel-error">'.$modx->lexicon($errorKey,$errorParameter).'</div>';
				}
			}

		}
		
		/* create babel-box with links to translations */
		$linkedResources = $babel->getLinkedResources($resource->get('id'));
		$outputLanguageItems = '';
		foreach($contextKeys as $contextKey) {
			/* for each (valid/existing) context of the context group a button will be displayed */
			$context = $modx->getObject('modContext', array('key' => $contextKey));
			if(!$context) {
				$modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
				continue;
			}
			$context->prepare();
			$cultureKey = $context->getOption('cultureKey',$modx->getOption('cultureKey'));
			/* url to which the form will post it's data */
			$formUrl = '?a='.$actions['resource/update'].'&amp;id='.$resource->get('id');
			if(isset($linkedResources[$contextKey])) {
				/* link to this context has been set */
				if($linkedResources[$contextKey] == $resource->get('id')) {
					/* don't show language layer for current resource */
					$showLayer = '';
				} else {
					$showLayer = 'yes';
				}
				$showTranslateButton = '';
				$showUnlinkButton = 'yes';
				$showSecondRow = '';
				$resourceId = $linkedResources[$contextKey];
				$resourceUrl = '?a='.$actions['resource/update'].'&amp;id='.$resourceId;
				if($resourceId == $resource->get('id')) {
					$className = 'selected';
				} else {
					$className = '';
				}
				
			} else {
				/* link to this context has not been set yet:
				 * -> show button to create translation */
				$showLayer = 'yes';
				$showTranslateButton = 'yes';
				$showUnlinkButton = '';
				$showSecondRow = 'yes';
				$resourceId = '';
				$resourceUrl = '#';
				$className = 'notset';
			}
			$placeholders = array(
				'formUrl' => $formUrl,
				'contextKey' => $contextKey,
				'cultureKey' => $cultureKey,
				'resourceId' => $resourceId,
				'resourceUrl' => $resourceUrl,
				'className' => $className,
				'showLayer' => $showLayer,
				'showTranslateButton' => $showTranslateButton,
				'showUnlinkButton' => $showUnlinkButton,
				'showSecondRow' => $showSecondRow,
			);
			$outputLanguageItems .= $babel->getChunk('mgr/babelBoxItem', $placeholders);
		}
		
		$output .= '<div id="babel-box">'.$errorMessage.$outputLanguageItems.'</div>';
		$modx->event->output($output);
		
		/* include CSS */
		$modx->regClientCSS($babel->config['cssUrl'].'babel.css?v=6');
		$modx->regClientStartupScript($babel->config['jsUrl'].'babel.js?v=3');
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
		$babel->sychronizeTvs($resource->get('id'));
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
		$babel->initBabelTv($resource);
		break;
}
return;