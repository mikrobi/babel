<?php
/**
 * Resolves install statistics
 *
 * @package glossary
 * @subpackage build
 *
 * @var array $options
 * @var xPDOObject $object
 * @var xPDOTransport $transport
 */

$url = 'https://treehillstudio.com/extras/package/statistics';
$params = [];

/** @var xPDO $modx */
$modx =& $object->xpdo;
$c = $modx->newQuery('transport.modTransportPackage');
$c->where(
    [
        'workspace' => 1,
        "(SELECT
            `signature`
            FROM {$modx->getTableName('transport.modTransportPackage')} AS `latestPackage`
            WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
            ORDER BY
                `latestPackage`.`version_major` DESC,
                `latestPackage`.`version_minor` DESC,
                `latestPackage`.`version_patch` DESC,
                IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',`release`) DESC,
                `latestPackage`.`release_index` DESC
                LIMIT 1,1) = `modTransportPackage`.`signature`",
    ]
);
$c->where(
    [
        'modTransportPackage.signature:LIKE' => $options['namespace'] . '-%',
        'modTransportPackage.installed:IS NOT' => null
    ]
);
$c->limit(1);

/** @var modTransportPackage $oldPackage */
$oldPackage = $modx->getObject('transport.modTransportPackage', $c);

$oldVersion = '';
if ($oldPackage) {
    $oldVersion = $oldPackage->get('version_major') .
        '.' . $oldPackage->get('version_minor') .
        '.' . $oldPackage->get('version_patch') .
        '-' . $oldPackage->get('release');
}

$version = '';
if ($transport->version) {
    $version = $transport->version;
}

$modxVersionObj = $modx->getObject('modSystemSetting', ['key' => 'settings_version']);
$modxVersion = ($modxVersionObj) ? $modxVersionObj->get('value') : '';
$managerLang = $modx->getOption('manager_language');

$action = '';
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $action = 'install';
        break;
    case xPDOTransport::ACTION_UPGRADE:
        $action = 'upgrade';
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $action = 'uninstall';
        break;
}

$params = [
    'name' => $options['namespace'],
    'uuid' => $modx->uuid ?? 'unknown',
    'host' => $modx->getOption('http_host', null, 'unknown'),
    'php_version' => phpversion(),
    'modx_version' => $modxVersion,
    'manager_lang' => $managerLang,
    'installation_type' => $action,
    'package_version_from' => $oldVersion,
    'package_version' => $version,
    'date' => time()
];

/**
 * Curl POST.
 */
$curl = curl_init();
if ($curl) {
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($curl);
    $responseInfo = curl_getinfo($curl);
    $curlError = curl_error($curl);
    curl_close($curl);
}

return true;
