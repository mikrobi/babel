<?php
/**
 * Add settings to build
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
$settings = array();

$settings['babel.contextKeys']= $modx->newObject('modSystemSetting');
$settings['babel.contextKeys']->fromArray(array(
    'key' => 'babel.contextKeys',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'babel',
    'area' => 'common',
),'',true,true);
$settings['babel.babelTvName']= $modx->newObject('modSystemSetting');
$settings['babel.babelTvName']->fromArray(array(
    'key' => 'babel.babelTvName',
    'value' => 'babelLanguageLinks',
    'xtype' => 'textfield',
    'namespace' => 'babel',
    'area' => 'common',
),'',true,true);
$settings['babel.syncTvs']= $modx->newObject('modSystemSetting');
$settings['babel.syncTvs']->fromArray(array(
    'key' => 'babel.syncTvs',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'babel',
    'area' => 'common',
),'',true,true);

return $settings;