<?php
/**
 * Get list resources
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectGetListProcessor;

class BabelResourceGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $defaultSortField = 'pagetitle';
    public $objectType = 'babel.resource';
    public $languageTopics = ['resource', 'babel:default'];
    public $permission = 'view';

    protected $search = ['pagetitle'];

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $ctx = $this->getProperty('context');
        if ($ctx) {
            $c->where([
                'context_key:=' => $ctx
            ]);
        }

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
                'id' => 0,
                'pagetitle' => '',
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
                'id' => $objectArray['id'],
                'pagetitle' => $objectArray['pagetitle'],
            ];
        }

        return $objectArray;
    }
}

return 'BabelResourceGetListProcessor';
