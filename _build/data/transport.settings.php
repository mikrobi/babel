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
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package babel
 */
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