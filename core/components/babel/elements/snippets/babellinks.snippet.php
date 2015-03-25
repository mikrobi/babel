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
 * BabelLinks snippet to display links to translated resources
 *
 * Based on ideas of Sylvain Aerni <enzyms@gmail.com>
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *         goldsky <goldsky@virtudraft.com>
 *
 * @package babel
 *
 * @param resourceId        optional: id of resource of which links to translations should be displayed. Default: current resource
 * @param tpl               optional: Chunk to display a language link. Default: babelLink
 * @param activeCls         optional: CSS class name for the current active language. Default: active
 * @param showUnpublished   optional: flag whether to show unpublished translations. Default: 0
 * @param showCurrent       optional: flag whether to show a link to a translation of the current language. Default: 1
 */
$babel = $modx->getService('babel','Babel',$modx->getOption('babel.core_path',null,$modx->getOption('core_path').'components/babel/').'model/babel/',$scriptProperties);

/* be sure babel and babel TV is loaded */
if (!($babel instanceof Babel) || !$babel->babelTv) return;

/* get snippet properties */
$resourceId = intval($modx->getOption('resourceId',$scriptProperties));
if (empty($resourceId)) {
    if (!empty($modx->resource) && is_object($modx->resource)) {
        $resourceId = $modx->resource->get('id');
    } else {
        return;
    }
}
$tpl = $modx->getOption('tpl',$scriptProperties,'babelLink');
$wrapperTpl = $modx->getOption('wrapperTpl',$scriptProperties);
$activeCls = $modx->getOption('activeCls',$scriptProperties,'active');
$showUnpublished = $modx->getOption('showUnpublished',$scriptProperties,0);
$showCurrent = $modx->getOption('showCurrent',$scriptProperties,0);
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,"\n");
$includeUnlinked = $modx->getOption('includeUnlinked',$scriptProperties,0);
$ignoreSiteStatus = $modx->getOption('ignoreSiteStatus',$scriptProperties,0);

if(!empty($modx->resource) && is_object($modx->resource) && $resourceId === $modx->resource->get('id')) {
    $contextKeys = $babel->getGroupContextKeys($modx->resource->get('context_key'));
    $resource = $modx->resource;
} else {
    $resource = $modx->getObject('modResource', $resourceId);
    if(!$resource) {
        return;
    }
    $contextKeys = $babel->getGroupContextKeys($resource->get('context_key'));
}

$linkedResources = $babel->getLinkedResources($resourceId);

$outputArray = array();
foreach($contextKeys as $contextKey) {
    if(!$showCurrent && $contextKey === $resource->get('context_key')) {
        continue;
    }
    if (!$includeUnlinked && !isset($linkedResources[$contextKey])) {
        continue;
    }
    $context = $modx->getObject('modContext', array('key' => $contextKey));
    if(!$context) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load context: '.$contextKey);
        continue;
    }
    $context->prepare();
    if (!$context->getOption('site_status', null, true) && !$ignoreSiteStatus) {
        continue;
    }
    $cultureKey = $context->getOption('cultureKey',$modx->getOption('cultureKey'));
    $translationAvailable = false;
    if(isset($linkedResources[$contextKey])) {
        $c = $modx->newQuery('modResource');
        $c->where(array(
            'id' => $linkedResources[$contextKey],
            'deleted:!=' => 1,
            'published:=' => 1,
        ));
        if ($showUnpublished) {
            $c->where(array(
                'OR:published:=' => 0,
            ));
        }
        $count = $modx->getCount('modResource',$c);
        if($count) {
            $translationAvailable = true;
        }
    }
    $getRequest = $_GET;
    unset($getRequest['id']);
    unset($getRequest[$modx->getOption('request_param_alias', null, 'q')]);
    unset($getRequest['cultureKey']);
    if($translationAvailable) {
        $url = $context->makeUrl($linkedResources[$contextKey],$getRequest,'full');
        $active = ($resource->get('context_key') == $contextKey) ? $activeCls : '';
        $placeholders = array(
            'cultureKey' => $cultureKey,
            'url' => $url,
            'active' => $active,
            'id' => $linkedResources[$contextKey]
        );

        if (!empty($toArray)) {
            $outputArray[] = $placeholders;
        } else {
            $chunk = $babel->getChunk($tpl,$placeholders);
            if(!empty($chunk)) {
                $outputArray[] = $chunk;
            }
        }
    } elseif ($includeUnlinked) {
        $url = $context->makeUrl($context->getOption('site_start'),$getRequest,'full');
        $active = ($resource->get('context_key') == $contextKey) ? $activeCls : '';
        $placeholders = array(
            'cultureKey' => $cultureKey,
            'url' => $url,
            'active' => $active,
            'id' => $context->getOption('site_start')
        );

        if (!empty($toArray)) {
            $outputArray[] = $placeholders;
        } else {
            $chunk = $babel->getChunk($tpl,$placeholders);
            if(!empty($chunk)) {
                $outputArray[] = $chunk;
            }
        }
    }
}

if (!empty($toArray)) {
    return '<pre>'.  print_r($outputArray, 1).'</pre>';
}

$output = implode($outputSeparator, $outputArray);
if (!empty($wrapperTpl)) {
    $output = $babel->getChunk($wrapperTpl,array(
        'babelLinks' => $output
    ));
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return;
}

return $output;