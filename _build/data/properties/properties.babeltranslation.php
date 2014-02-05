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
 * Default properties for BabelTranslation snippet
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'resourceId',
        'desc' => 'babeltranslation.resourceId',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'babel:properties',
    ),
	array(
        'name' => 'contextKey',
        'desc' => 'babeltranslation.contextKey',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'babel:properties',
    ),
    array(
        'name' => 'cultureKey',
        'desc' => 'babeltranslation.cultureKey',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'babel:properties',
    ),
    array(
        'name' => 'showUnpublished',
        'desc' => 'babeltranslation.showUnpublished',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'babel:properties',
    ),
);

return $properties;