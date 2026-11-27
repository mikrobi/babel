<?php
/**
 * Update a system setting
 *
 * @package babel
 * @subpackage processors
 */

require_once dirname(__FILE__) . '/update.class.php';

/**
 * Class BabelSystemSettingsUpdateFromGridProcessor
 */
class BabelSystemSettingsUpdateFromGridProcessor extends BabelSystemSettingsUpdateProcessor
{
    /**
     * {@inheritDoc}
     * @return bool|string|null
     */
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = json_decode($data, true);
        if (!is_array($properties)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}

return 'BabelSystemSettingsUpdateFromGridProcessor';
