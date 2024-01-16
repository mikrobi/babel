<?php
/**
 * Resolves creating/removing babel events.
 *
 * @package babel
 * @subpackage build
 */

/**
 * @param modX $modx
 * @param string $name
 * @param integer $service see https://github.com/modxcms/revolution/blob/2.x/core/model/modx/modx.class.php#L2005-L2010
 * @return bool
 */
function createEvent(modX &$modx, $name, $service = 0)
{
    $success = true;
    $ct = $modx->getCount('modEvent', [
        'name' => $name
    ]);
    if (empty($ct)) {
        /** @var modEvent $event */
        $event = $modx->newObject('modEvent');
        $event->fromArray([
            'name' => $name,
            'service' => $service,
            'groupname' => 'Babel'
                          ], '', true, true);
        if ($event->save()) {
            $modx->log(xPDO::LOG_LEVEL_INFO, 'System event ' . $name . ' was created.');
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, 'System event ' . $name . ' was not created.');
            $success = false;
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_INFO, 'System event ' . $name . ' already exists.');
    }
    return $success;
}

/**
 * @param modX $modx
 * @param string $name
 * @return bool
 */
function removeEvent(modX &$modx, $name)
{
    $success = true;
    /** @var modEvent $event */
    $event = $modx->getObject('modEvent', [
        'name' => $name
    ]);
    if ($event) {
        $success = $event->remove();
        if ($success) {
            $modx->log(xPDO::LOG_LEVEL_INFO, 'System event ' . $name . ' was removed.');
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, 'System event ' . $name . ' was not removed.');
        }
    }
    return $success;
}

$babelEvents = [
    'OnBabelDuplicate', // invoked on duplicating the resource in a new language context
    'OnBabelLink', // invoked on link the resource with a target resource
    'OnBabelUnlink' // invoked on unlink the resource from a target resource
];

$success = true;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($babelEvents as $babelEvent) {
            $created = createEvent($object->xpdo, $babelEvent, 2);
            $success = $success && $created;
        }
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        foreach ($babelEvents as $babelEvent) {
            $removed = removeEvent($object->xpdo, $babelEvent);
            $success = $success && $removed;
        }
        break;
}
return $success;
