<?php
/**
 * Babel
 *
 * Copyright 2010-2024 by Jakob Class <jakob.class@gmail.com>
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
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * @author Jakob Class <jakob.class@gmail.com>
 * @author Rico Goldsky <goldsky@virtudraft.com>
 * @author Joshua Luckers <joshualuckers@me.com>
 * @author Thomas Jakobi <office@treehillstudio.com>
 *
 * @package babel
 * @subpackage classfile
 */

namespace mikrobi\Babel;

use LanguageSubtagRegistry;
use modChunk;
use modResource;
use modSystemEvent;
use modTemplateVar;
use modX;
use mikrobi\Babel\Helper\Parse;
use MODX\Revolution\modContext;
use xPDO;
use xPDOCacheManager;

class Babel
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public modX $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'babel';

    /**
     * The package name
     * @var string $packageName
     */
    public string $packageName = 'Babel';

    /**
     * The version
     * @var string $version
     */
    public string $version = '3.2.0-rc1';

    /**
     * The class config
     * @var array $config
     */
    public $config = [];

    /**
     * A collection of preprocessed chunk values.
     * @var array
     */
    protected $chunks = [];

    /**
     * A reference to the babel TV which is used to store linked resources.
     * The linked resources are stored using this syntax: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
     * Example: web:1;de:4;es:7;fr:10
     * @var modTemplateVar $babelTv
     */
    public $babelTv = null;

    /**
     * An associative array which maps context keys to the context groups.
     * @var array $contextKeyToGroup
     */
    protected $contextKeyToGroup = [];

    /**
     * @var Parse $parse
     */
    public $parse = null;

    /**
     * Babel constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $config An array of config. Optional.
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $config, $this->namespace);

        $corePath = $this->getOption('core_path', $config, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $config, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $config, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->config = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $config);

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');

        $this->packageName = $this->modx->lexicon('babel');

        // Add default config
        $this->config = array_merge($this->config, [
            'debug' => $this->getBooleanOption('debug', [], false),
            'modxversion' => $modxversion['version'],
            'permissions' => $this->getPermissions(),
            'contextKeys' => $this->modx->getOption($this->namespace . '.contextKeys', null, ''),
            'restrictToGroup' => $this->getBooleanOption('restrictToGroup', [], true),
            'displayText' => $this->modx->getOption($this->namespace . '.displayText', null, 'language'),
            'syncTvs' => $this->modx->getOption($this->namespace . '.syncTvs', null, ''),
            'babelTvName' => $this->modx->getOption($this->namespace . '.babelTvName', null, 'babelLanguageLinks'),
        ]);

        $contextKeys = $this->getOption('contextKeys');
        $this->contextKeyToGroup = $this->decodeContextKeySetting($contextKeys);
        $this->config['contexts'] = array_keys($this->contextKeyToGroup);

        $babelTvName = $this->getOption('babelTvName');
        $this->babelTv = $modx->getObject('modTemplateVar', ['name' => $babelTvName]);
        if (!$this->babelTv) {
            $this->modx->log(xPDO::LOG_LEVEL_WARN, 'Could not load babel TV: ' . $babelTvName . ' will try to create it ...');
            $fields = [
                'name' => $babelTvName,
                'type' => 'hidden',
                'default_text' => '',
                'caption' => $this->modx->lexicon('babel.tv_caption'),
                'description' => $this->modx->lexicon('babel.tv_description')
            ];
            $this->babelTv = $modx->newObject('modTemplateVar', $fields);
            if ($this->babelTv->save()) {
                $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Created babel TV: ' . $babelTvName);
            } else {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not create babel TV: ' . $babelTvName);
            }
        }

        $this->parse = new Parse($modx);
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $config An array of config that override local config.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption(string $key, array $config = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($config != null && array_key_exists($key, $config)) {
                $option = $config[$key];
            } elseif (array_key_exists($key, $this->config)) {
                $option = $this->config[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Get Boolean Option
     *
     * @param string $key
     * @param array $config
     * @param mixed $default
     * @return bool
     */
    public function getBooleanOption(string $key, array $config = [], $default = null): bool
    {
        $option = $this->getOption($key, $config, $default);
        return ($option === 'true' || $option === true || $option === '1' || $option === 1);
    }

    /**
     * Set a local configuration option.
     *
     * @param string $key The option key to be set.
     * @param mixed $value The value.
     */
    public function setOption(string $key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        $access = [];
        $perms = [
            'babel_settings',
            'settings',
        ];
        foreach ($perms as $p) {
            $access[$p] = $this->modx->hasPermission($p);
        }
        return $access;
    }

    /**
     * Creates an associative array which maps context keys to there
     * context groups out of an $contextKeyString
     *
     * @param string $contextKeyString Example: ctx1,ctx2;ctx3,ctx4,ctx5;ctx5,ctx6
     * @return array An associative array which maps context keys to the context groups.
     */
    public function decodeContextKeySetting($contextKeyString)
    {
        $contextKeyToGroup = [];
        if (!empty($contextKeyString)) {
            $contextGroups = explode(';', $contextKeyString);
            $contextGroups = array_map('trim', $contextGroups);
            foreach ($contextGroups as $contextGroup) {
                $groupContextKeys = explode(',', $contextGroup);
                $groupContextKeys = array_map('trim', $groupContextKeys);
                foreach ($groupContextKeys as $contextKey) {
                    if (!empty($contextKey)) {
                        $contextKeyToGroup[$contextKey] = $groupContextKeys;
                    }
                }
            }
        }
        return $contextKeyToGroup;
    }

    /**
     * Synchronizes the TVs of the specified resource with its translated resources.
     *
     * @param int $resourceId id of resource.
     */
    public function synchronizeTvs($resourceId)
    {
        $linkedResources = $this->getLinkedResources($resourceId);
        /* check if Babel TV has been initiated for the specified resource */
        if (empty($linkedResources)) {
            $linkedResources = $this->initBabelTvById($resourceId);
        }

        /* synchronize the TVs of linked resources */
        $syncTvs = $this->getOption('syncTvs');
        if (empty($syncTvs) || !is_array($syncTvs)) {
            /* there are no TVs to synchronize */
            return;
        }

        foreach ($syncTvs as $tvId) {
            /* go through each TV which should be synchronized */
            $tv = $this->modx->getObject('modTemplateVar', $tvId);
            if (!$tv) {
                continue;
            }
            $tvValue = $tv->getValue($resourceId);
            foreach ($linkedResources as $linkedResourceId) {
                /* go through each linked resource */
                if ($resourceId == $linkedResourceId) {
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
    public function getGroupContextKeys($contextKey)
    {
        $contextKeys = [];
        if ($this->getOption('restrictToGroup')) {
            if (isset($this->contextKeyToGroup[$contextKey]) && is_array($this->contextKeyToGroup[$contextKey])) {
                $contextKeys = $this->contextKeyToGroup[$contextKey];
            }
        } else {
            $contextKeys = $this->getOption('contexts');
        }
        return $contextKeys;
    }

    /**
     * Creates a duplicate of the specified resource in the specified context.
     *
     * @param modResource $resource
     * @param string $contextKey
     */
    public function duplicateResource($resource, $contextKey)
    {
        /* determine parent id of new resource */
        $newParentId = null;
        $parentId = $resource->get('parent');
        if ($parentId != null) {
            $linkedParentResources = $this->getLinkedResources($parentId);
            if (isset($linkedParentResources[$contextKey])) {
                $newParentId = $linkedParentResources[$contextKey];
            }
        }
        /* create new resource */
        $newResource = $this->modx->newObject($resource->get('class_key'));
        $newResource->fromArray($resource->toArray('', true), '', false, true);
        $newResource->set('id', 0);
        $newResource->set('pagetitle', $resource->get('pagetitle') . ' ' . $this->modx->lexicon('babel.translation_pending'));
        $newResource->set('parent', intval($newParentId));
        $newResource->set('createdby', $this->modx->user->get('id'));
        $newResource->set('createdon', time());
        $newResource->set('editedby', 0);
        $newResource->set('editedon', 0);
        $newResource->set('deleted', false);
        $newResource->set('deletedon', 0);
        $newResource->set('deletedby', 0);
        $newResource->set('published', false);
        $newResource->set('publishedon', 0);
        $newResource->set('publishedby', 0);
        $newResource->set('context_key', $contextKey);
        if ($newResource->save()) {
            /* copy all TV values */
            $templateVarResources = $resource->getMany('TemplateVarResources');
            foreach ($templateVarResources as $oldTemplateVarResource) {
                $newTemplateVarResource = $this->modx->newObject('modTemplateVarResource');
                $newTemplateVarResource->set('contentid', $newResource->get('id'));
                $newTemplateVarResource->set('tmplvarid', $oldTemplateVarResource->get('tmplvarid'));
                $newTemplateVarResource->set('value', $oldTemplateVarResource->get('value'));
                $newTemplateVarResource->save();
            }

            /* set parent of duplicate as a folder */
            if ($newParentId) {
                $newParent = $this->modx->getObject('modResource', $newParentId);
                if ($newParent) {
                    $newParent->set('isfolder', 1);
                    $newParent->save();
                }
            }

            /* invoke OnDocFormSave event */
            $this->modx->invokeEvent('OnDocFormSave', [
                'mode' => modSystemEvent::MODE_NEW,
                'id' => $newResource->get('id'),
                'resource' => &$newResource
            ]);
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not duplicate resource: ' . $resource->get('id') . ' in context: ' . $contextKey);
            $newResource = null;
        }
        return $newResource;
    }

    /**
     * Init/reset the Babel TV of the specified resource.
     *
     * @param modResource $resource resource object.
     * @return array associative array with linked resources (array contains only the resource itself).
     */
    public function initBabelTv($resource)
    {
        $linkedResources = [$resource->get('context_key') => $resource->get('id')];
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
     * @return void
     */
    public function initBabelTvsRecursive(&$modx, &$babel, $id = null, $depth = 100)
    {
        if ($id && $depth > 0) {
            $q = $modx->newQuery('modResource');
            $q->select(['id']);
            $q->where(['parent' => $id]);
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
    public function initBabelTvById($resourceId)
    {
        /** @var modResource $resource */
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
    public function updateBabelTv($resourceIds, $linkedResources, $clearCache = true)
    {
        if (!is_array($resourceIds)) {
            $resourceIds = [intval($resourceIds)];
        }
        $newValue = $this->encodeTranslationLinks($linkedResources);
        foreach ($resourceIds as $resourceId) {
            $this->babelTv->setValue($resourceId, $newValue);
        }
        $this->babelTv->save();
        if ($clearCache) {
            $this->modx->cacheManager->refresh();
        }
    }

    /**
     * Returns an associative array of the linked resources of the specified resource.
     *
     * @param int $resourceId id of resource.
     * @return array associative array with linked resources: [contextKey] = resourceId.
     */
    public function getLinkedResources($resourceId)
    {
        return $this->decodeTranslationLinks($this->babelTv->getValue($resourceId));
    }

    /**
     * Creates an associative array of linked resources out of string.
     *
     * @param string $linkedResourcesString string which contains the translation links: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
     * @return array associative array with linked resources: [contextKey] = resourceId.
     */
    public function decodeTranslationLinks($linkedResourcesString)
    {
        $linkedResources = [];
        if (!empty($linkedResourcesString)) {
            $contextResourcePairs = explode(';', $linkedResourcesString);
            foreach ($contextResourcePairs as $contextResourcePair) {
                $contextResourcePair = explode(':', $contextResourcePair);
                $contextKey = $contextResourcePair[0];
                $resourceId = intval($contextResourcePair[1]);
                $linkedResources[$contextKey] = $resourceId;
            }
        }
        return $linkedResources;
    }

    /**
     * Creates a string which contains the translation links out of an associative array.
     *
     * @param array $linkedResources associative array with linked resources: [contextKey] = resourceId
     * @return string which contains the translation links: [contextKey1]:[resourceId1];[contextKey2]:[resourceId2]
     */
    public function encodeTranslationLinks($linkedResources)
    {
        if (!is_array($linkedResources)) {
            return '';
        }
        $contextResourcePairs = [];
        foreach ($linkedResources as $contextKey => $resourceId) {
            $contextResourcePairs[] = $contextKey . ':' . intval($resourceId);
        }
        return implode(';', $contextResourcePairs);
    }

    /**
     * Removes all translation links to the specified resource.
     *
     * @param int $resourceId id of resource.
     */
    public function removeLanguageLinksToResource($resourceId)
    {
        /* search for resource which contain a ':$resourceId' in their Babel TV */
        $templateVarResources = $this->modx->getCollection('modTemplateVarResource', [
            'value:LIKE' => '%:' . $resourceId . '%'
        ]);
        if (!is_array($templateVarResources)) {
            return;
        }
        foreach ($templateVarResources as $templateVarResource) {
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
    public function removeLanguageLinksToContext($contextKey)
    {
        /* search for resource which contain a '$contextKey:' in their Babel TV */
        $templateVarResources = $this->modx->getCollection('modTemplateVarResource', [
            'value:LIKE' => '%' . $contextKey . ':%'
        ]);
        if (!is_array($templateVarResources)) {
            return;
        }
        foreach ($templateVarResources as $templateVarResource) {
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
        $setting = $this->modx->getObject('modSystemSetting', [
            'key' => 'babel.contextKeys'
        ]);
        if ($setting) {
            /* remove all spaces */
            $newValue = str_replace(' ', '', $setting->get('value'));
            /* replace context key with leading comma */
            $newValue = str_replace(',' . $contextKey, '', $newValue);
            /* replace context key without leading comma (if still present) */
            $newValue = str_replace($contextKey, '', $newValue);
            $setting->set('value', $newValue);
            if ($setting->save()) {
                $this->modx->reloadConfig();
                $this->modx->cacheManager->deleteTree($this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'cache/mgr/smarty/', [
                    'deleteTop' => false,
                    'skipDirs' => false,
                    'extensions' => ['.cache.php', '.php'],
                ]);
            }
        }
    }

    /**
     * Gets the contextKey by cultureKey
     *
     * @param string $cultureKey the culture key
     * @return string context key
     */
    public function getContextKey($cultureKey)
    {
        /* Search for Context Setting */
        $ctxSetting = $this->modx->getObject("modContextSetting", [
            "key" => "cultureKey",
            "value" => $cultureKey
        ]);
        return (($ctxSetting) ? $ctxSetting->get("context_key") : false);
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
    public function getChunk($name, array $properties = [])
    {
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk', ['name' => $name]);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name, $this->getOption('chunkSuffix'));
                if (!$chunk) {
                    return false;
                }
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
    private function _getTplChunk($name, $suffix = '.chunk.tpl')
    {
        $chunk = false;
        $f = $this->getOption('chunksPath') . strtolower($name) . $suffix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name', $name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    /**
     * Get placeholders to create language selection menu.
     * Used in plugin and processors.
     *
     * @param modResource $resource
     * @return array menu
     */
    public function getMenu($resource)
    {
        $menu = [];
        $contextKeys = $this->getGroupContextKeys($resource->get('context_key'));
        $linkedResources = $this->getLinkedResources($resource->get('id'));
        if (empty($linkedResources)) {
            /* always be sure that the Babel TV is set */
            $this->initBabelTv($resource);
        }

        $languages = $this->getLanguages();
        foreach ($contextKeys as $contextKey) {
            /* for each (valid/existing) context of the context group a button will be displayed */
            /** @var modContext $context */
            $context = $this->modx->getObject('modContext', ['key' => $contextKey]);
            if (!$context) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not load context: ' . $contextKey);
                continue;
            }
            $context->prepare();
            $cultureKey = $context->getOption('cultureKey', $this->modx->getOption('cultureKey'));
            $linkResource = null;
            $resourceId = 0;
            if (isset($linkedResources[$contextKey])) {
                $resourceId = $linkedResources[$contextKey];
                /** @var modResource $linkResource */
                $linkResource = $this->modx->getObject('modResource', $resourceId);
            }
            if ($linkResource) {
                $resourceUrl = '?a=resource/update&id=' . $resourceId;
                $resourceTitle = $linkResource->get('pagetitle');
            } else {
                $resourceId = '';
                $resourceUrl = '#';
                $resourceTitle = '';
            }
            if ($this->getOption('displayText') == 'context') {
                $displayText = $context->get('name') . ' (' . $contextKey . ')';
            } elseif ($this->getOption('displayText') == 'combination') {
                $displayText = $context->get('name') . ' (' . $contextKey . ') - ' . $languages[$cultureKey]['Description'] . ' (' . (!empty($cultureKey) ? $cultureKey : $contextKey) . ')';
            } else {
                $displayText = $languages[$cultureKey]['Description'] . ' (' . (!empty($cultureKey) ? $cultureKey : $contextKey) . ')';
            }

            $placeholders = [
                'resourceId' => $resourceId,
                'resourceUrl' => $resourceUrl,
                'resourceTitle' => $resourceTitle,
                'displayText' => $displayText,
            ];
            $menu[$contextKey] = $placeholders;
        }

        return $menu;
    }

    /**
     * Get all languages in array
     *
     * @return array Languages' info
     */
    public function getLanguages()
    {
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => 'babel',
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$this->modx->getOption('cache_resource_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ];
        $languages = $this->modx->cacheManager->get('languages', $cacheOptions);
        if (!$languages) {
            $ianaLstr = new LanguageSubtagRegistry();
            $ianaLstr->readSource($this->getOption('corePath') . 'src/LanguageSubtagRegistry/language-subtag-registry');
            $languages = $ianaLstr->languagesAssocArray('Subtag');
            $this->modx->cacheManager->set('languages', $languages, 0, $cacheOptions);
        }
        foreach ($languages as $k => $v) {
            $languages[$k]['Description'] = $v['Description'][0];
        }

        return $languages;
    }
}
