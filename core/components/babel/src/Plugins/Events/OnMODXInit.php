<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;
use mikrobi\Babel\Babel;

use \MODX\Revolution\modConnectorResponse;

class OnMODXInit extends Plugin
{
    
    public function process()
    {
        if(isset($_REQUEST['action'])) {
            if (in_array($_REQUEST['action'], $this->babel->config['overwriteProcessors'])) {
                $connectorRequestClass = $this->modx->getOption('modConnectorRequest.class', null, $this->babel->config['isModx3'] ? \MODX\Revolution\modConnectorRequest::class : 'modConnectorRequest');
                $this->modx->config['modRequest.class'] = $connectorRequestClass;
                $this->modx->getRequest();
                $this->modx->request->sanitizeRequest();

                $_REQUEST['action'] = str_replace('context', '', strtolower($_REQUEST['action']));
                $this->modx->request->handleRequest([
                    'processors_path' => $this->babel->config['processorsPath'] . 'mgr/context/',
                    'location' => '',
                ]);;
            }
        }
    }
}
