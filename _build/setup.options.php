<?php
/**
 * Babel
 *
 * Copyright 2010 by Jakob Class <jakob.class@class-zec.de>
 *
 * This file is part of Babel.
 *
 * Babel is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Babel is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Babel; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package babel
 */
/**
 * Build the setup options form.
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
/* set some default values */
$contexts = $modx->getCollection('modContext');
$contextKeys = array();
foreach($contexts as $context) {
	$contextKey = $context->get('key');
	if($contextKey != 'mgr') {
		$contextKeys[] = $contextKey;
	}
}
$values = array(
    'contextKeys' => implode(',',$contextKeys),
    'babelTvName' => 'babelLanguageLinks',
	'syncTvs' => '',
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting',array('key' => 'babel.contextKeys'));
        if ($setting != null) { $values['contextKeys'] = $setting->get('value'); }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => 'babel.babelTvName'));
        if ($setting != null) { $values['babelTvName'] = $setting->get('value'); }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => 'babel.syncTvs'));
        if ($setting != null) { $values['syncTvs'] = $setting->get('value'); }
        unset($setting);
    break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

$output = '<label for="babel-contextKeys">Context Keys (comma-separated):</label><br />
<input type="text" name="contextKeys" id="babel-contextKeys" value="'.$values['contextKeys'].'" />
Hint: For advanced configuration you can even set context groups separated by semi-colons. Example: "web,ctx1,ctx2;ctx3,ctx4;ctx5,ctx6" please refer to readme.txt for details.
<br /><br />

<label for="babel-babelTvName">Name of Babel TV:</label><br />
<input type="text" name="babelTvName" id="babel-babelTvName" value="'.$values['babelTvName'].'" />
<br /><br />

<label for="babel-syncTvs">IDs of TVs to be synchronized (comma-separated):</label><br />
<input type="text" name="syncTvs" id="babel-syncTvs" value="'.$values['syncTvs'].'" />
Hint: Leave blank if no TVs should be synchronized';

return $output;