<?php
/**
 * Abstract processor
 *
 * @package babel
 * @subpackage processors
 */

namespace mikrobi\Babel\Processors;

use mikrobi\Babel\Babel;
use modProcessor;
use modX;

/**
 * Class Processor
 */
abstract class Processor extends modProcessor
{
    public $languageTopics = ['babel:default'];

    /** @var Babel $babel */
    public $babel;

    /**
     * {@inheritDoc}
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('babel.core_path', null, $this->modx->getOption('core_path') . 'components/babel/');
        $this->babel = $this->modx->getService('babel', Babel::class, $corePath . 'model/babel/');
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function checkPermissions()
    {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    abstract public function process();

    /**
     * Get a boolean property.
     * @param string $k
     * @param mixed $default
     * @return bool
     */
    public function getBooleanProperty($k, $default = null)
    {
        return ($this->getProperty($k, $default) === 'true' || $this->getProperty($k, $default) === true || $this->getProperty($k, $default) === '1' || $this->getProperty($k, $default) === 1);
    }
}
