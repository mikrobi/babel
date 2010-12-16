<?php
/**
 * This file is the main class file for Babel.
 * 
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 */
class Babel {
	
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
     * @var array A collection of properties to adjust Quip behaviour.
     */
    public $config = array();    
    /**
     * @access public
     * @var	modTemplateVar A reference to the babel TV which is used to store linked resources.
     * 		The linked resources are stored using this syntax: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
     * 		Example: web:1;de:4;es:7;fr:10
     */
    public $babelTv = null;

    /**
     * The Babel Constructor.
     *
     * This method is used to create a new Babel object.
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config A collection of properties that modify Quip
     * behaviour.
     * @return Babel A unique Babel instance.
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        
        $corePath = $this->modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/');
        $assetsUrl = $this->modx->getOption('babel.assets_url',null,$modx->getOption('assets_url').'components/babel/');
        
        $contextKeysOption = $this->modx->getOption('babel.contextKeys',$config,'');
		$contextGroups = explode(';', $contextKeysOption);
		$contextGroups = array_map('trim', $contextGroups);
		/* maps a context key to it's context group */
		$contextKeyToGroup = array();
		foreach($contextGroups as $contextGroup) {
			$groupContextKeys = explode(',',$contextGroup);
			$groupContextKeys = array_map('trim', $groupContextKeys);
			foreach($groupContextKeys as $contextKey) {
				$contextKeyToGroup[$contextKey] = $groupContextKeys;
			}			
		}
		$syncTvs = $this->modx->getOption('babel.syncTvs',$config,'');
		$syncTvs = explode(',', $syncTvs);
		$syncTvs = array_map('trim', $syncTvs);
		$babelTvName = $this->modx->getOption('babel.babelTvName',$config,'babelLanguageLinks');

        $this->config = array_merge(array(
            'corePath' => $corePath,
            'chunksPath' => $corePath.'elements/chunks/',
        	'chunkSuffix' => '.chunk.tpl',
       		'cssUrl' => $assetsUrl.'css/',
        	'contextKeyToGroup' => $contextKeyToGroup,
        	'syncTvs' => $syncTvs,
        	'babelTvName' => $babelTvName,
        ),$config);

        /* load babel lexicon */
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('babel:default');
        }
	
        /* load babel TV */
        
		$this->babelTv = $modx->getObject('modTemplateVar',array('name' => $babelTvName));
		if(!$this->babelTv) {
			$this->modx->log(modX::LOG_LEVEL_WARN, 'Could not load babel TV: '.$babelTvName.' will try to create it...');
			$fields = array(
				'name' => $babelTvName,
				'type' => 'text',
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
	 * Returns an array with the context keys of the specified context's group.
	 * 
	 * @param string $contextKey key of context.
	 */
	public function getGroupContextKeys($contextKey) {
		$contextKeys = array();
		if(isset($this->config['contextKeyToGroup'][$contextKey]) && is_array($this->config['contextKeyToGroup'][$contextKey])) {
			$contextKeys = $this->config['contextKeyToGroup'][$contextKey];
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
			$linkedParentResources = $this->parseBabelTv($parentId);
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
        $newResource->set('alias', '');
		$newResource->set('context_key', $contextKey);
		if($newResource->save()) {
			/* copy all TV values */
		    $tvs = $resource->getMany('TemplateVarResources');
	        foreach ($tvs as $oldTemplateVarResource) {
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
					$newParent->set('is_folder', 1);
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
	 * Parses the the babel TV of the specified resource.
	 * 
	 * @param int $resourceId id of resource
	 * 
	 * @return array associative array with linked resources: [contextKey] = resourceId
	 */
	public function decodeBabelTv($resourceId) {
		$tvValue = $this->babelTv->getValue($resourceId);
		$linkedResources = array();
		if(!empty($tvValue)) {
			$contextResourcePairs = explode(';', $tvValue);
			foreach($contextResourcePairs as $contextResourcePair) {
				$contextResourcePair = explode(':', $contextResourcePair);
				$contextKey = $contextResourcePair[0];
				$resourceId = $contextResourcePair[1];
				$linkedResources[$contextKey] = $resourceId;
			}
		}
		return $linkedResources;		
	}
	
	/**
	 * Creates an string which can be stored in the babel TV out of an associative array with linked resource
	 * 
	 * @param array associative array with linked resources: [contextKey] = resourceId
	 * 
	 * return string which can be stored in the babel TV: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
	 */
	public function encodeBabelTv($linkedResources) {
		if(!is_array($linkedResources)) {
			return;
		}
		$contextResourcePairs = array();
		foreach($linkedResources as $contextKey => $resourceId){
			$contextResourcePairs[] = $contextKey.':'.$resourceId;
		}
		return implode(';', $contextResourcePairs);
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
	
}



