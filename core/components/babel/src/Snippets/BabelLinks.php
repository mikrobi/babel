<?php
/**
 * BabelLinks Snippet
 *
 * @package babel
 * @subpackage snippet
 */

namespace mikrobi\Babel\Snippets;

use xPDO;

/**
 * Class BabelLinks
 */
class BabelLinks extends Snippet
{
    /**
     * Get default snippet properties.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            'resourceId::int' => (!empty($this->modx->resource) && is_object($this->modx->resource)) ? $this->modx->resource->get('id') : 0,
            'tpl' => 'tplBabellink',
            'wrapperTpl' => '',
            'activeCls' => 'active',
            'showUnpublished::bool' => false,
            'showCurrent::bool' => false,
            'includeUnlinked::bool' => false,
            'ignoreSiteStatus::bool' => false,
            'toArray::bool' => false,
            'toPlaceholder' => '',
            'outputSeparator' => "\n",
        ];
    }

    /**
     * Execute the hook and return the result.
     *
     * @return bool
     * @throws /Exception
     */
    public function execute()
    {
        $resourceId = $this->getProperty('resourceId');
        if (empty($resourceId)) {
            return '';
        }

        if (!empty($this->modx->resource) && is_object($this->modx->resource) && $resourceId === $this->modx->resource->get('id')) {
            $contextKeys = $this->babel->getGroupContextKeys($this->modx->resource->get('context_key'));
            $resource = $this->modx->resource;
        } else {
            $resource = $this->modx->getObject('modResource', $resourceId);
            if (!$resource) {
                return '';
            }
            $contextKeys = $this->babel->getGroupContextKeys($resource->get('context_key'));
        }

        $linkedResources = $this->babel->getLinkedResources($resourceId);
        $languages = $this->babel->getLanguages();
        $outputArray = [];
        foreach ($contextKeys as $contextKey) {
            if (!$this->getProperty('showCurrent') && $contextKey === $resource->get('context_key')) {
                continue;
            }
            if (!$this->getProperty('includeUnlinked') && !isset($linkedResources[$contextKey])) {
                continue;
            }
            $context = $this->modx->getObject('modContext', ['key' => $contextKey]);
            if (!$context) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not load context: ' . $contextKey);
                continue;
            }
            $context->prepare();
            if (!$context->getOption('site_status', null, true) && !$this->getProperty('ignoreSiteStatus')) {
                continue;
            }
            $cultureKey = $context->getOption('cultureKey', $this->modx->getOption('cultureKey'));
            $translationAvailable = false;
            if (isset($linkedResources[$contextKey])) {
                $c = $this->modx->newQuery('modResource');
                $c->where([
                    'id' => $linkedResources[$contextKey],
                    'deleted:!=' => 1,
                    'published:=' => 1,
                ]);
                if ($this->getProperty('showUnpublished')) {
                    $c->where([
                        'OR:published:=' => 0,
                    ]);
                }
                $count = $this->modx->getCount('modResource', $c);
                if ($count) {
                    $translationAvailable = true;
                }
            }
            $getRequest = $this->modx->request->getParameters();
            unset($getRequest['id']);
            unset($getRequest[$this->modx->getOption('request_param_alias', null, 'q')]);
            unset($getRequest['cultureKey']);
            if ($translationAvailable) {
                $url = $context->makeUrl($linkedResources[$contextKey], $getRequest, 'full');
                $active = ($resource->get('context_key') == $contextKey) ? $this->getProperty('activeCls') : '';
                $placeholders = [
                    'active' => $active,
                    'contextKey' => $contextKey,
                    'contextName' => $context->get('name'),
                    'cultureKey' => $cultureKey,
                    'id' => $linkedResources[$contextKey],
                    'language' => $languages[$cultureKey]['Description'],
                    'url' => $url,
                ];

                if ($this->getProperty('toArray')) {
                    $outputArray[] = $placeholders;
                } else {
                    $chunk = $this->babel->parse->getChunk($this->getProperty('tpl'), $placeholders);
                    if (!empty($chunk)) {
                        $outputArray[] = $chunk;
                    }
                }
            } elseif ($this->getProperty('includeUnlinked')) {
                $url = $context->makeUrl($context->getOption('site_start'), $getRequest, 'full');
                $active = ($resource->get('context_key') == $contextKey) ? $this->getProperty('activeCls') : '';
                $placeholders = [
                    'cultureKey' => $cultureKey,
                    'url' => $url,
                    'active' => $active,
                    'id' => $context->getOption('site_start'),
                    'language' => $languages[$cultureKey]['Description'],
                ];

                if ($this->getProperty('toArray')) {
                    $outputArray[] = $placeholders;
                } else {
                    $chunk = $this->babel->parse->getChunk($this->getProperty('tpl'), $placeholders);
                    if (!empty($chunk)) {
                        $outputArray[] = $chunk;
                    }
                }
            }
        }

        if ($this->getProperty('toArray')) {
            return '<pre>' . print_r($outputArray, 1) . '</pre>';
        }

        $output = implode($this->getProperty('outputSeparator'), $outputArray);
        if (!empty($this->getProperty('wrapperTpl'))) {
            $output = $this->babel->parse->getChunk($this->getProperty('wrapperTpl'), [
                'babelLinks' => $output
            ]);
        }

        if (!empty($this->getProperty('toPlaceholder'))) {
            $this->modx->setPlaceholder($this->getProperty('toPlaceholder'), $output);
            return '';
        }

        return $output;
    }
}
