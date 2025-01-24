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
    const OVERWRITE_PROCESSOR_PATH = [
        "Context/Setting/Create",
        "Context/Setting/Update",
        "Context/Setting/UpdateFromGrid",
        "Context/Setting/Remove"
    ];
    public function process()
    {
        if(in_array($_REQUEST['action'], self::OVERWRITE_PROCESSOR_PATH)) {
            $className = end(explode('/', $_REQUEST['action']));
            $connectorRequestClass = $this->modx->getOption('modConnectorRequest.class', null, \MODX\Revolution\modConnectorRequest::class);
            $this->modx->config['modRequest.class'] = $connectorRequestClass;
            $this->modx->getRequest(\MODX\Revolution\modConnectorRequest::class);
            $this->modx->request->sanitizeRequest();
            
            $_REQUEST['action'] = str_replace('Context', '', $_REQUEST['action']);
            $this->modx->request->handleRequest([
                'processors_path' => $this->babel->config['processorsPath'] . 'mgr/Context/',
                'location' => '',
            ]);;
        }
    }
}
