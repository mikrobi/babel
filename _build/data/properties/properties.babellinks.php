<?php
/**
 * Default properties for BabelLinks snippet
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'tpl',
        'desc' => 'babel.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'babelLink',
        'lexicon' => 'babel:properties',
    ),
    array(
        'name' => 'activeCls',
        'desc' => 'babel.activeCls_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'active',
        'lexicon' => 'babel:properties',
    ),
);

return $properties;