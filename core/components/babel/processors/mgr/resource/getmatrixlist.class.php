<?php
/**
 * Get list resource matrix
 *
 * @package babel
 * @subpackage processors
 */

use mikrobi\Babel\Processors\ObjectGetListProcessor;

class BabelResourceGetMatrixListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $defaultSortField = 'id';
    public $objectType = 'babel.resource';
    public $languageTopics = ['resource', 'babel:default'];
    public $permission = 'view';

    protected $search = ['pagetitle'];
    protected $contexts = [];

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function initialize()
    {
        $this->contexts = array_map('trim', explode(',', $this->getProperty('contexts')));

        return parent::initialize();
    }

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
        } else {
            $c->where([
                'context_key:IN' => $this->babel->getOption('contexts')
            ]);
        }

        return $c;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = parent::prepareRow($object);
        $objectArray['pagetitle'] = htmlspecialchars($objectArray['pagetitle']);

        $linkedResources = $this->babel->getLinkedResources($objectArray['id']);
        $contextKeyToGroup = $this->babel->contextKeyToGroup;
        foreach ($this->contexts as $ctx) {
            $objectArray['linkedres_id_' . $ctx] = '';
            $objectArray['linkedres_pagetitle_' . $ctx] = '';
            if ($objectArray['context_key'] === $ctx) {
                $objectArray['linkedres_id_' . $ctx] = 'x';
                $objectArray['linkedres_pagetitle_' . $ctx] = 'x';
            }  else if(!in_array($ctx, $contextKeyToGroup[$objectArray['context_key']])) {
                $objectArray['linkedres_id_' . $ctx] = 'x';
                $objectArray['linkedres_pagetitle_' . $ctx] = 'x';
            } else {
                if (!empty($linkedResources[$ctx])) {
                    $objectArray['linkedres_id_' . $ctx] = $linkedResources[$ctx];
                    $resource = $this->modx->getObject('modResource', $linkedResources[$ctx]);
                    if ($resource) {
                        $objectArray['linkedres_pagetitle_' . $ctx] = htmlspecialchars($resource->get('pagetitle'));
                    }
                }
            }
        }

        return $objectArray;
    }
}

return 'BabelResourceGetMatrixListProcessor';
