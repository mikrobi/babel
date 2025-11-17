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
            'sortby::sortby' => 'babel',
            'sortdir::sortdir' => 'asc',
            'restrictToGroup::bool' => $this->babel->getOption('restrictToGroup'),
            'useRequestProperties::bool' => true,
            'toArray::bool' => false,
            'toPlaceholder' => '',
            'outputSeparator' => "\n",
        ];
    }

    /**
     * @param $value
     * @return string
     */
    protected function getSortby($value): string
    {
        if ($value == 'babel' || $value == '') {
            return '';
        } else {
            return $this->modx->escape($value);
        }
    }

    /**
     * @param $value
     * @return string
     */
    protected function getSortdir($value): string
    {
        return (in_array(strtolower($value), ['asc', 'desc',])) ? strtolower($value) : 'asc';
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
            $resource = $this->modx->resource;
        } else {
            $resource = $this->modx->getObject('modResource', $resourceId);
            if (!$resource) {
                return '';
            }
        }
        $contextKeys = $this->babel->getGroupContextKeys($resource->get('context_key'), $this->getProperty('restrictToGroup'));

        $contexts = [];
        $c = $this->modx->newQuery('modContext', ['key:IN' => $contextKeys]);
        if (!empty($this->getProperty('sortby'))) {
            $c->sortby($this->getProperty('sortby'), $this->getProperty('sortdir'));
        } elseif (!empty($contextKeys)) {
            $c->sortby('FIELD(modContext.key, "' . implode('","', $contextKeys) . '")');
        }
        /** @var \modContext $context */
        foreach ($this->modx->getIterator('modContext', $c) as $context) {
            $contexts[$context->key] = $context;
        }
        if ($diff = array_diff($contextKeys, array_keys($contexts))) {
            foreach ($diff as $contextKey) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not load context: ' . $contextKey);
            }
        }

        $linkedResources = $this->babel->getLinkedResources($resourceId);
        $languages = $this->babel->getLanguages();
        $outputArray = [];
        $this->modx->lexicon->load('babel:languages');
        foreach ($contexts as $context) {
            $contextKey = $context->get('key');
            if (!$this->getProperty('showCurrent') && $contextKey === $resource->get('context_key')) {
                continue;
            }
            if (!$this->getProperty('includeUnlinked') && !isset($linkedResources[$contextKey])) {
                continue;
            }
            $context->prepare();
            if (!$context->getOption('site_status', null, true) && !$this->getProperty('ignoreSiteStatus')) {
                continue;
            }
            $cultureKey = $context->getOption('cultureKey', $this->modx->getOption('cultureKey'));
            $languageName = (!empty($languages[$cultureKey]['Description'])) ? $languages[$cultureKey]['Description'] : $this->modx->lexicon('babel.language_' . $cultureKey);
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
            if ($this->getProperty('useRequestProperties')) {
                $getRequest = $this->modx->request->getParameters();
                unset($getRequest['id']);
                unset($getRequest[$this->modx->getOption('request_param_alias', null, 'q')]);
                unset($getRequest['cultureKey']);
            } else {
                $getRequest = [];
            }
            if ($translationAvailable) {
                $url = $context->makeUrl($linkedResources[$contextKey], $getRequest, 'full');
                $active = ($resource->get('context_key') == $contextKey) ? $this->getProperty('activeCls') : '';
                $placeholders = [
                    'active' => $active,
                    'contextKey' => $contextKey,
                    'contextName' => $context->get('name'),
                    'cultureKey' => $cultureKey,
                    'id' => $linkedResources[$contextKey],
                    'language' => $languageName,
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
                    'active' => $active,
                    'contextKey' => $contextKey,
                    'contextName' => $context->get('name'),
                    'cultureKey' => $cultureKey,
                    'id' => $context->getOption('site_start'),
                    'language' => $languageName,
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
