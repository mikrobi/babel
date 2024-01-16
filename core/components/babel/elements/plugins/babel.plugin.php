<?php
/**
 * Babel Plugin
 *
 * @package babel
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'mikrobi\Babel\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('babel.core_path', null, $modx->getOption('core_path') . 'components/babel/');
/** @var Babel $babel */
$babel = $modx->getService('babel', Babel::class, $corePath . 'model/babel/', [
    'core_path' => $corePath
]);

if ($babel) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className . ' could not be initialized!', '', 'Babel Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className . ' was not found!', '', 'Babel Plugin');
    }
}

return;
