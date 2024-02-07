<?php
/**
 * Abstract remove processor
 *
 * @package babel
 * @subpackage processors
 */

namespace mikrobi\Babel\Processors;

use mikrobi\Babel\Babel;
use modObjectRemoveProcessor;
use modX;
use PDO;

/**
 * Class ObjectRemoveProcessor
 */
class ObjectRemoveProcessor extends modObjectRemoveProcessor
{
    public $languageTopics = ['babel:default'];

    /** @var Babel */
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
        $this->babel = $this->modx->getService('babel', 'Babel', $corePath . 'model/babel/');
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function afterRemove()
    {
        $this->clearCache();

        return parent::afterRemove();
    }

    /**
     * Clear the context and the lexicon topics cache.
     */
    protected function clearCache()
    {
        $query = $this->modx->newQuery('modContext');
        $query->select($this->modx->escape('key'));
        if ($query->prepare() && $query->stmt->execute()) {
            $contextKeys = $query->stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $contextKeys = [];
        }

        $this->modx->cacheManager->refresh(
            [
                'lexicon_topics' => [],
                'babel' => [],
                'resource' => ['contexts' => array_diff($contextKeys, ['mgr'])],
            ]
        );
    }
}
