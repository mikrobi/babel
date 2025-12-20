<?php
/**
 * Setup options
 *
 * @package babel
 * @subpackage build
 * @var modX $modx
 * @var array $options
 */

// Defaults
$contexts = $modx->getCollection('modContext');
$contextKeys = [];
foreach ($contexts as $context) {
    $contextKey = $context->get('key');
    if ($contextKey != 'mgr') {
        $contextKeys[] = $contextKey;
    }
}
$defaults = [
    'contextKeys' => implode(',', $contextKeys),
    'babelTvName' => 'babelLanguageLinks',
    'syncTvs' => '',
    'syncFields' => '',
];

$output = '<style type="text/css">
    #modx-setupoptions-panel { display: none; }
    #modx-setupoptions-form p { margin-bottom: 10px; }
    #modx-setupoptions-form h2 { margin-bottom: 15px; }
</style>';

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $output .= '<h2>Install Babel</h2>
        <p>Thanks for installing Babel. This open source extra is currently
        maintained by Treehill Studio - MODX development in Münsterland.</p>

        <p>During the installation, we will collect some statistical data (the
        hostname, the MODX UUID, the PHP version and the MODX version of your
        MODX installation). Your data will be kept confidential and under no
        circumstances be used for promotional purposes or disclosed to third
        parties. We only like to know the usage count of this package.</p>
        
        <p>If you install this package, you are giving us your permission to
        collect, process and use that data for statistical purposes.</p>
        
        <p>Babel will be installed. Please review the installation options carefully.</p><br>';

        $output .= '<div style="position: relative">
                <label for="babel-contextKeys">Context Keys (comma-separated):</label>
                <input type="text" name="contextKeys" id="babel-contextKeys" width="450" value="' . $defaults['contextKeys'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                <p>Hint: For advanced configuration you can even set context groups separated by semicolons. Example: "web,ctx1,ctx2;ctx3,ctx4;ctx5,ctx6" please refer to readme.txt for details.</p>
            </div>';
        $output .= '<div style="position: relative">
                <label for="babel-babelTvName">Name of Babel TV:</label>
                <input type="text" name="babelTvName" id="babel-babelTvName" width="450" value="' . $defaults['babelTvName'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
            </div>';
        $output .= '<div style="position: relative">
                <label for="babel-syncTvs">IDs of TVs to be synchronized (comma-separated):</label>
                <input type="text" name="syncTvs" id="babel-syncTvs" width="450" value="' . $defaults['syncTvs'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                <p>Hint: Leave blank if no TVs should be synchronized.</p>
            </div>';
        $output .= '<div style="position: relative">
                <label for="babel-syncFields">IDs of resource fields to be synchronized (comma-separated):</label>
                <input type="text" name="syncFields" id="babel-syncFields" width="450" value="' . $defaults['syncFields'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                <p>Hint: Leave blank if no resource fields should be synchronized.</p>
            </div>';
        break;
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting', ['key' => 'babel.contextKeys']);
        $values['contextKeys'] = ($setting) ? $setting->get('value') : $defaults['contextKeys'];
        unset($setting);

        $setting = $modx->getObject('modSystemSetting', ['key' => 'babel.babelTvName']);
        $values['babelTvName'] = ($setting) ? $setting->get('value') : $defaults['babelTvName'];
        unset($setting);

        $setting = $modx->getObject('modSystemSetting', ['key' => 'babel.syncTvs']);
        $values['syncTvs'] = ($setting) ? $setting->get('value') : $defaults['syncTvs'];
        unset($setting);
        
        $setting = $modx->getObject('modSystemSetting', ['key' => 'babel.syncFields']);
        $values['syncFields'] = ($setting) ? $setting->get('value') : $defaults['syncFields'];
        unset($setting);

        $output .= '<h2>Upgrade Babel</h2>
        <p>Babel will be upgraded. This open source extra is currently
        maintained by Treehill Studio - MODX development in Münsterland.</p>

        <p>During the installation, we will collect some statistical data (the
        hostname, the MODX UUID, the PHP version and the MODX version of your
        MODX installation). Your data will be kept confidential and under no
        circumstances be used for promotional purposes or disclosed to third
        parties. We only like to know the usage count of this package.</p>

        <p>If you upgrade this package, you are giving us your permission to
        collect, process and use that data for statistical purposes.</p>

        <p>Please review the installation options carefully.</p><br>';

        $output .= '<div style="position: relative">
                <label for="babel-contextKeys">Context Keys (comma-separated):</label>
                <input type="text" name="contextKeys" id="babel-contextKeys" width="450" value="' . $values['contextKeys'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                <p>Hint: For advanced configuration you can even set context groups separated by semicolons. Example: "web,ctx1,ctx2;ctx3,ctx4;ctx5,ctx6" please refer to readme.txt for details.</p>
            </div>';
        $output .= '<div style="position: relative">
                <label for="babel-babelTvName">Name of Babel TV:</label>
                <input type="text" name="babelTvName" id="babel-babelTvName" width="450" value="' . $values['babelTvName'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
            </div>';
        $output .= '<div style="position: relative">
                <label for="babel-syncTvs">IDs of TVs to be synchronized (comma-separated):</label>
                <input type="text" name="syncTvs" id="babel-syncTvs" width="450" value="' . $values['syncTvs'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                <p>Hint: Leave blank if no TVs should be synchronized.</p>
            </div>';
        $output .= '<div style="position: relative">
                <label for="babel-syncFields">IDs of resource fields to be synchronized (comma-separated):</label>
                <input type="text" name="syncFields" id="babel-syncFields" width="450" value="' . $defaults['syncFields'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                <p>Hint: Leave blank if no resource fields should be synchronized.</p>
            </div>';
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}


return $output;

