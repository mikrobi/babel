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
 * This file is the main class file for Babel.
 *
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *         goldsky <goldsky@virtudraft.com>
 *
 * @package babel
 */
class Babel {

    const VERSION = '3.0.0';
    const RELEASE = 'beta4';

    /**
     * @access protected
     * @var array A collection of preprocessed chunk values.
     */
    protected $chunks = array();
    /**
     * @access public
     * @var modX A reference to the modX object.
     */
    public $modx = null;
    /**
     * @access public
     * @var array A collection of properties to adjust Babel behaviour.
     */
    public $config = array();
    /**
     * @access public
     * @var	modTemplateVar A reference to the babel TV which is used to store linked resources.
     * 		The linked resources are stored using this syntax: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
     * 		Example: web:1;de:4;es:7;fr:10
     */
    public $babelTv = null;

    protected $contextKeyToGroup;

    /**
     * The Babel Constructor.
     *
     * This method is used to create a new Babel object.
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config A collection of properties that modify Babel
     * behaviour.
     * @return Babel A unique Babel instance.
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/');
        $assetsUrl = $this->modx->getOption('babel.assets_url',null,$modx->getOption('assets_url').'components/babel/');

        $contextKeysOption = $this->modx->getOption('babel.contextKeys',$config,'');
		$this->contextKeyToGroup = $this->decodeContextKeySetting($contextKeysOption);
		$syncTvsOption = $this->modx->getOption('babel.syncTvs',$config,'');
		$syncTvs = array();
		if(!empty($syncTvsOption)) {
			$syncTvs = explode(',', $syncTvsOption);
			$syncTvs = array_map('intval', $syncTvs);
		}
		$babelTvName = $this->modx->getOption('babel.babelTvName',$config,'babelLanguageLinks');

        $this->config = array_merge(array(
            'version' => self::VERSION . '-' . self::RELEASE,
            'basePath' => $corePath,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'chunksPath' => $corePath.'elements/chunks/',
        	'chunkSuffix' => '.chunk.tpl',
            'templatesPath' => $corePath . 'templates/',
       		'cssUrl' => $assetsUrl.'css/',
        	'jsUrl' => $assetsUrl.'js/',
        	'syncTvs' => $syncTvs,
        	'babelTvName' => $babelTvName,
        	'connector_url' => $assetsUrl . 'conn/mgr.php',
        ),$config);

        /* load babel lexicon */
        if (!$this->modx->lexicon) {
            $this->modx->getService('lexicon','modLexicon');
        }
        $this->modx->lexicon->load('babel:default');

        /* load babel TV */
		$this->babelTv = $modx->getObject('modTemplateVar',array('name' => $babelTvName));
		if(!$this->babelTv) {
			$this->modx->log(modX::LOG_LEVEL_WARN, 'Could not load babel TV: '.$babelTvName.' will try to create it...');
			$fields = array(
				'name' => $babelTvName,
				'type' => 'hidden',
				'default_text' => '',
				'caption' => $this->modx->lexicon('babel.tv_caption'),
				'description'=>$this->modx->lexicon('babel.tv_description'));
			$this->babelTv = $modx->newObject('modTemplateVar', $fields);
			if($this->babelTv->save()) {
				$this->modx->log(modX::LOG_LEVEL_INFO, 'Created babel TV: '.$babelTvName);
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create babel TV: '.$babelTvName);
			}
		}
	}


	/**
	 * Synchronizes the TVs of the specified resource with its translated resources.
	 *
	 * @param int $resourceId id of resource.
	 */
	public function synchronizeTvs($resourceId) {
		$linkedResources = $this->getLinkedResources($resourceId);
		/* check if Babel TV has been initiated for the specified resource */
		if(empty($linkedResources)) {
			$linkedResources = $this->initBabelTvById($resourceId);
		}

		/* synchronize the TVs of linked resources */
		$syncTvs = $this->config['syncTvs'];
		if(empty($syncTvs) || !is_array($syncTvs)) {
			/* there are no TVs to synchronize */
			return;
		}

		foreach($syncTvs as $tvId){
			/* go through each TV which should be synchronized */
			$tv = $this->modx->getObject('modTemplateVar',$tvId);
			if(!$tv) {
				continue;
			}
			$tvValue = $tv->getValue($resourceId);
			foreach($linkedResources as $linkedResourceId){
				/* go through each linked resource */
				if($resourceId == $linkedResourceId) {
					/* don't synchronize resource with itself */
					continue;
				}
				$tv->setValue($linkedResourceId, $tvValue);
			}
			$tv->save();
		}

		$this->modx->cacheManager->refresh();
	}

	/**
	 * Returns an array with the context keys of the specified context's group.
	 *
	 * @param string $contextKey key of context.
	 */
	public function getGroupContextKeys($contextKey) {
		$contextKeys = array();
		if(isset($this->contextKeyToGroup[$contextKey]) && is_array($this->contextKeyToGroup[$contextKey])) {
			$contextKeys = $this->contextKeyToGroup[$contextKey];
		}
		return $contextKeys;
	}

	/**
	 * Creates a duplicate of the specified resource in the specified context.
	 *
	 * @param modResource $resource
	 * @param string $contextKey
	 */
	public function duplicateResource(&$resource, $contextKey) {
		/* determine parent id of new resource */
		$newParentId = null;
		$parentId = $resource->get('parent');
		if ($parentId != null) {
			$linkedParentResources = $this->getLinkedResources($parentId);
			if(isset($linkedParentResources[$contextKey])) {
				$newParentId = $linkedParentResources[$contextKey];
			}
		}
		/* create new resource */
		$newResource = $this->modx->newObject($resource->get('class_key'));
		$newResource->fromArray($resource->toArray('', true), '', false, true);
		$newResource->set('id',0);
		$newResource->set('pagetitle', $resource->get('pagetitle').' '.$this->modx->lexicon('babel.translation_pending'));
		$newResource->set('parent',intval($newParentId));
		$newResource->set('createdby',$this->modx->user->get('id'));
		$newResource->set('createdon',time());
		$newResource->set('editedby',0);
		$newResource->set('editedon',0);
		$newResource->set('deleted',false);
		$newResource->set('deletedon',0);
		$newResource->set('deletedby',0);
		$newResource->set('published',false);
		$newResource->set('publishedon',0);
		$newResource->set('publishedby',0);
		$newResource->set('context_key', $contextKey);
		if($newResource->save()) {
			/* copy all TV values */
		    $templateVarResources = $resource->getMany('TemplateVarResources');
	        foreach ($templateVarResources as $oldTemplateVarResource) {
	            $newTemplateVarResource = $this->modx->newObject('modTemplateVarResource');
	            $newTemplateVarResource->set('contentid',$newResource->get('id'));
	            $newTemplateVarResource->set('tmplvarid',$oldTemplateVarResource->get('tmplvarid'));
	            $newTemplateVarResource->set('value',$oldTemplateVarResource->get('value'));
	            $newTemplateVarResource->save();
	        }

			/* set parent of duplicate as a folder */
			if($newParentId) {
				$newParent = $this->modx->getObject('modResource', $newParentId);
				if($newParent) {
					$newParent->set('isfolder', 1);
					$newParent->save();
				}
			}
		} else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not duplicate resource: '.$resource->get('id').' in context: '.$contextKey);
			$newResource = null;
		}
		return $newResource;
	}

	/**
	 * Creates an associative array which maps context keys to there
	 * context groups out of an $contextKeyString
	 *
	 * @param string $contextKeyString example: ctx1,ctx2;ctx3,ctx4,ctx5;ctx5,ctx6
	 *
	 * @return array associative array which maps context keys to there
	 * context groups.
	 */
	public function decodeContextKeySetting($contextKeyString) {
		$contextKeyToGroup = array();
		if(!empty($contextKeyString)) {
			$contextGroups = explode(';', $contextKeyString);
			$contextGroups = array_map('trim', $contextGroups);
			foreach($contextGroups as $contextGroup) {
				$groupContextKeys = explode(',',$contextGroup);
				$groupContextKeys = array_map('trim', $groupContextKeys);
				foreach($groupContextKeys as $contextKey) {
					if(!empty($contextKey)) {
						$contextKeyToGroup[$contextKey] = $groupContextKeys;
					}
				}
			}
		}
		return $contextKeyToGroup;
	}

	/**
	 * Init/reset the Babel TV of the specified resource.
	 *
	 * @param modResource $resource resource object.
	 *
	 * @return  array associative array with linked resources (array contains only the resource itself).
	 */
	public function initBabelTv($resource) {
		$linkedResources = array ($resource->get('context_key') => $resource->get('id'));
		$this->updateBabelTv($resource->get('id'), $linkedResources, false);
		return $linkedResources;
	}

    /**
     * Run \Babel\initBabelTv recursively
     *
     * @author https://github.com/manu37
     *
     * @param object $modx
     * @param object $babel
     * @param int $id
     * @param int $depth
     *
     * @return void
     */
    public function initBabelTvsRecursive(&$modx, &$babel, $id = null, $depth = 100) {
        if ($id && $depth > 0) {
            $q = $modx->newQuery('modResource');
            $q->select(array('id'));
            $q->where(array('parent' => $id));
            $children = $modx->getCollection('modResource', $q);
            foreach ($children as $child) {
                $processDepth = $depth - 1;
                $this->initBabelTvsRecursive($modx, $babel, $child->get('id'), $processDepth);
            }
            $this->initBabelTvById($id);
        }
    }

    /**
	 * Init/reset the Babel TV of a resource specified by the id of the resource.
	 *
	 * @param int $resourceId id of resource (int).
	 */
	public function initBabelTvById($resourceId) {
		$resource = $this->modx->getObject('modResource', $resourceId);
		return $this->initBabelTv($resource);
	}

    /**
	 * Updates the Babel TV of the specified resource(s).
	 *
	 * @param mixed $resourceIds id of resource or array of resource ids which should be updated.
	 * @param array $linkedResources associative array with linked resources: [contextKey] = resourceId
	 * @param boolean $clearCache flag to empty cache after update.
	 */
	public function updateBabelTv($resourceIds, $linkedResources, $clearCache = true) {
		if(!is_array($resourceIds)) {
			$resourceIds = array(intval($resourceIds));
		}
		$newValue = $this->encodeTranslationLinks($linkedResources);
		foreach($resourceIds as $resourceId){
			$this->babelTv->setValue($resourceId,$newValue);
		}
		$this->babelTv->save();
		if($clearCache) {
			$this->modx->cacheManager->refresh();
		}
		return;
	}

	/**
	 * Returns an associative array of the linked resources of the specified resource.
	 *
	 * @param int $resourceId id of resource.
	 *
	 * @return array associative array with linked resources: [contextKey] = resourceId.
	 */
	public function getLinkedResources($resourceId) {
		return $this->decodeTranslationLinks($this->babelTv->getValue($resourceId));
	}

	/**
	 * Creates an associative array of linked resources out of string.
	 *
	 * @param string $linkedResourcesString string which contains the translation links: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
	 *
	 * @return array associative array with linked resources: [contextKey] = resourceId.
	 */
	public function decodeTranslationLinks($linkedResourcesString) {
		$linkedResources = array();
		if(!empty($linkedResourcesString)) {
			$contextResourcePairs = explode(';', $linkedResourcesString);
			foreach($contextResourcePairs as $contextResourcePair) {
				$contextResourcePair = explode(':', $contextResourcePair);
				$contextKey = $contextResourcePair[0];
				$resourceId = intval($contextResourcePair[1]);
				$linkedResources[$contextKey] = $resourceId;
			}
		}
		return $linkedResources;
	}

	/**
	 * Creates an string which contains the translation links out of an associative array.
	 *
	 * @param array $linkedResources associative array with linked resources: [contextKey] = resourceId
	 *
	 * return string which contains the translation links: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
	 */
	public function encodeTranslationLinks($linkedResources) {
		if(!is_array($linkedResources)) {
			return;
		}
		$contextResourcePairs = array();
		foreach($linkedResources as $contextKey => $resourceId){
			$contextResourcePairs[] = $contextKey.':'.intval($resourceId);
		}
		return implode(';', $contextResourcePairs);
	}

	/**
	 * Removes all translation links to the specified resource.
	 *
	 * @param int $resourceId id of resource.
	 */
	public function removeLanguageLinksToResource($resourceId) {
		/* search for resource which contain a ':$resourceId' in their Babel TV */
		$templateVarResources = $this->modx->getCollection('modTemplateVarResource', array(
			'value:LIKE' => '%:'.$resourceId.'%'));
		if(!is_array($templateVarResources)) {
			return;
		}
		foreach($templateVarResources as $templateVarResource) {
			/* go through each resource and remove the link of the specified resource */
			$oldValue = $templateVarResource->get('value');
			$linkedResources = $this->decodeTranslationLinks($oldValue);
			/* array maps context keys to resource ids
			 * -> search for the context key of the specified resource id */
			$contextKey = array_search($resourceId, $linkedResources);
            /* sanity check, is the contextKey really a context in babel's settings? */
            if (array_key_exists($contextKey, $this->contextKeyToGroup)) {
                unset($linkedResources[$contextKey]);
                $newValue = $this->encodeTranslationLinks($linkedResources);
                $templateVarResource->set('value', $newValue);
                $templateVarResource->save();
            }
        }
	}

	/**
	 * Removes all translation links to the specified context.
	 *
	 * @param int $contextKey key of context.
	 */
	public function removeLanguageLinksToContext($contextKey) {
		/* search for resource which contain a '$contextKey:' in their Babel TV */
		$templateVarResources = $this->modx->getCollection('modTemplateVarResource', array(
			'value:LIKE' => '%'.$contextKey.':%'));
		if(!is_array($templateVarResources)) {
			return;
		}
		foreach($templateVarResources as $templateVarResource) {
			/* go through each resource and remove the link of the specified context */
			$oldValue = $templateVarResource->get('value');
			$linkedResources = $this->decodeTranslationLinks($oldValue);
			/* array maps context keys to resource ids */
			unset($linkedResources[$contextKey]);
			$newValue = $this->encodeTranslationLinks($linkedResources);
			$templateVarResource->set('value', $newValue);
			$templateVarResource->save();
		}
		/* finaly clean the babel.contextKeys setting */
		$setting = $this->modx->getObject('modSystemSetting',array(
    		'key' => 'babel.contextKeys'));
		if($setting) {
			/* remove all spaces */
			$newValue = str_replace(' ','',$setting->get('value'));
			/* replace context key with leading comma */
			$newValue = str_replace(','.$contextKey,'',$newValue);
			/* replace context key without leading comma (if still present) */
			$newValue = str_replace($contextKey,'',$newValue);
			$setting->set('value', $newValue);
			if($setting->save()) {
				$this->modx->reloadConfig();
				$this->modx->cacheManager->deleteTree($this->modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
				   'deleteTop' => false,
				    'skipDirs' => false,
				    'extensions' => array('.cache.php','.php'),
				));
			}
		}
	}

	/**
     * Gets the contextKey by cultureKey
     *
     * @param string $cultureKey the culture key
     * @return string context key
     */
    public function getContextKey($cultureKey) {
		/* Search for Context Setting */
		$ctxSetting = $this->modx->getObject("modContextSetting", array(
			"key" => "cultureKey",
			"value" => $cultureKey
		));
		return (($ctxSetting)? $ctxSetting->get("context_key") : false);
	}

    /**
	* Gets a Chunk and caches it; also falls back to file-based templates
	* for easier debugging.
	*
	* @access public
	* @param string $name The name of the Chunk
	* @param array $properties The properties for the Chunk
	* @return string The processed content of the Chunk
	*/
    public function getChunk($name,array $properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name,$this->config['chunkSuffix']);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
	* Returns a modChunk object from a template file.
	*
	* @access private
	* @param string $name The name of the Chunk. Will parse to name.chunk.tpl by default.
	* @param string $suffix The suffix to add to the chunk filename.
	* @return modChunk/boolean Returns the modChunk object if found, otherwise
	* false.
	*/
    private function _getTplChunk($name,$suffix = '.chunk.tpl') {
        $chunk = false;
		$f = $this->config['chunksPath'].strtolower($name).$suffix;
		if (file_exists($f)) {
			$o = file_get_contents($f);
			$chunk = $this->modx->newObject('modChunk');
			$chunk->set('name',$name);
			$chunk->setContent($o);
		}
        return $chunk;
    }

    /**
     * Get placeholders to create language selection menu.
     * Used in plugin and processors.
     *
     * @param object $resource
     * @return array menu
     */
    public function getMenu($resource) {
        $menu = array();
		/* grab manager actions IDs */
		$actions = $this->modx->request->getAllActionIDs();
		$contextKeys = $this->getGroupContextKeys($resource->get('context_key'));
		$linkedResources = $this->getLinkedResources($resource->get('id'));
		if(empty($linkedResources)) {
			/* always be sure that the Babel TV is set */
			$this->initBabelTv($resource);
		}
		foreach($contextKeys as $contextKey) {
			/* for each (valid/existing) context of the context group a button will be displayed */
			$context = $this->modx->getObject('modContext', array('key' => $contextKey));
			if(!$context) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
				continue;
			}
			$context->prepare();
			$cultureKey = $context->getOption('cultureKey',$this->modx->getOption('cultureKey'));
			if(isset($linkedResources[$contextKey])) {
				$resourceId = $linkedResources[$contextKey];
				$resourceUrl = '?a='.$actions['resource/update'].'&id='.$resourceId;
				$linkResource = $this->modx->getObject('modResource', $linkedResources[$contextKey]);
                $resourceTitle = $linkResource->get('pagetitle');
			} else {
				$resourceId = '';
				$resourceUrl = '#';
                $resourceTitle = '';
			}
			$placeholders = array(
				'resourceId' => $resourceId,
				'resourceUrl' => $resourceUrl,
				'resourceTitle' => $resourceTitle,
				'displayText' => $this->modx->lexicon('babel.language_'.$cultureKey)." ($contextKey)",
			);
            $menu[$contextKey] = $placeholders;
        }

        return $menu;
    }
}