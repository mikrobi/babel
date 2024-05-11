<?php

/**
 * BabelTranslation Snippet
 *
 * @package babel
 * @subpackage snippet
 */

namespace mikrobi\Babel\Snippets;

use modResource;

/**
 * Class BabelTranslation
 */
class BabelTranslation extends Snippet
{
    /**
     * Get default snippet properties.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            'resourceId::explodeSeparatedInt' => (!empty($this->modx->resource) && is_object($this->modx->resource)) ? (string)$this->modx->resource->get('id') : '',
            'contextKey' => '',
            'cultureKey' => $this->modx->getOption('cultureKey'),
            'showUnpublished::bool' => false,
            'toPlaceholder' => ''
        ];
    }

    /**
     * Execute the snippet and return the result.
     *
     * @return string
     * @throws /Exception
     */
    public function execute()
    {
        $resourceIds = $this->getProperty('resourceId');
        if (empty($resourceIds)) {
            return '';
        }

        $contextKey = $this->getProperty('contextKey');
        if (empty($contextKey)) {
            $cultureKey = $this->getProperty('cultureKey');
            $contextKey = $this->babel->getContextKey($cultureKey);
        }
        $showUnpublished = $this->getProperty('showUnpublished');

        /* determine ids of translated resource */
        $output = [];
        foreach ($resourceIds as $resourceId) {
            $linkedResource = $this->babel->getLinkedResources($resourceId);
            if (isset($linkedResource[$contextKey])) {
                /** @var modResource $resource */
                $resource = $this->modx->getObject('modResource', $linkedResource[$contextKey]);
                if ($resource && ($showUnpublished || $resource->get('published') == 1)) {
                    $output[] = $resource->get('id');
                }
            }
        }

        $result = implode(',', $output);

        if (!empty($this->getProperty('toPlaceholder'))) {
            $this->modx->setPlaceholder($this->getProperty('toPlaceholder'), $result);
            return '';
        }

        return $result;
    }

    /**
     * Explode a separated value to an array of integers.
     *
     * @param mixed $value
     * @param string $separator
     * @return array
     */
    protected function getExplodeSeparatedInt($value, $separator = ',')
    {
        if (is_int($value)) {
            return [$value];
        } else {
            return (is_string($value) && $value !== '') ? array_map('intval', explode($separator, $value)) : [];
        }
    }
}
