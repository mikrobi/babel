<?php
/**
 * Babel connector
 *
 * @package babel
 * @subpackage connector
 *
 * @var modX $modx
 */

require_once dirname(__FILE__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('babel.core_path', null, $modx->getOption('core_path') . 'components/babel/');
/** @var Babel $babel */
$babel = $modx->getService('babel', Babel::class, $corePath . 'model/babel/', [
    'core_path' => $corePath
]);

// Handle request
$modx->request->handleRequest([
    'processors_path' => $babel->getOption('processorsPath'),
    'location' => '',
]);
