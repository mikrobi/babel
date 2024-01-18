<?php
/**
 * Get list contexts
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectGetListProcessor;

class BabelContextGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modContext';
    public $defaultSortField = 'key';
    public $objectType = 'babel.context';
    public $languageTopics = ['context', 'babel:default'];
    public $permission = 'view_context';

    protected $search = ['key', 'name'];

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $success = parent::initialize();

        $this->setDefaultProperties([
            'search' => '',
            'exclude' => 'mgr',
        ]);

        return $success;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $exclude = $this->getProperty('exclude') ? array_map('trim', explode(',', $this->getProperty('exclude'))) : [];
        if ($exclude) {
            $c->where([
                'key:NOT IN' => $exclude
            ]);
        }
        $c->where([
            'key:IN' => $this->babel->getOption('contexts')
        ]);

        return $c;
    }

    /**
     * {@inheritDoc}
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        if (!$this->getProperty('id') && $this->getBooleanProperty('combo', false)) {
            $empty = [
                'key' => '',
                'name' => $this->modx->lexicon('babel.all'),
            ];
            $list[] = $empty;
        }

        return $list;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = parent::prepareRow($object);
        if ($this->getBooleanProperty('combo', false)) {
            $objectArray = [
                'key' => $objectArray['key'],
                'name' => $objectArray['name'],
            ];
        }

        return $objectArray;
    }
}

return 'BabelContextGetListProcessor';
