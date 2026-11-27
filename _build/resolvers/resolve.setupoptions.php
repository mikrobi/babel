<?php
/**
 * Resolve setup options
 *
 * @package babel
 * @subpackage build
 *
 * @var array $options
 * @var xPDOTransport $transport
 */

/** @var modX $modx */
$modx = $transport->xpdo;

$success = true;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $settings = [
            'contextKeys',
            'babelTvName',
            'syncTvs',
            'syncFields',
        ];
        foreach ($settings as $key) {
            if (isset($options[$key])) {
                $setting = $modx->getObject('modSystemSetting', [
                    'key' => 'babel.' . $key
                ]);
                if ($setting != null) {
                    $setting->set('value', $modx->getOption($key, $options));
                    $setting->save();
                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'The babel.' . $key . ' system setting was not found and can\'t be updated.');
                }
            }
        }

        $success = true;
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}
return $success;
