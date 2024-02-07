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
            'resourceId::int' => (!empty($this->modx->resource) && is_object($this->modx->resource)) ? $this->modx->resource->get('id') : 0,
            'contextKey' => '',
            'cultureKey' => $this->modx->getOption('cultureKey'),
            'showUnpublished::bool' => false
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

        $resourceIds = array_map('trim', explode(',', $resourceIds));
        $contextKey = $this->getProperty('contextKey');
        if (empty($contextKey)) {
            $cultureKey = $this->getProperty('cultureKey');
            $contextKey = $this->babel->getContextKey($cultureKey);
        }
        $showUnpublished = $this->getProperty('showUnpublished');

        /* determine ids of translated resource */
        $output = [];
        foreach($resourceIds as $resourceId) {
            $linkedResource = $this->babel->getLinkedResources($resourceId);
            if (isset($linkedResource[$contextKey])) {
                /** @var modResource $resource */
                $resource = $this->modx->getObject('modResource', $linkedResource[$contextKey]);
                if ($resource && ($showUnpublished || $resource->get('published') == 1)) {
                    $output[] = $resource->get('id');
                }
            }
        }
        return implode(',', $output);
    }
}
