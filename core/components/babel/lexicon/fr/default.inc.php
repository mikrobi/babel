<?php
/**
 * Babel French language file
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage lexicon
 * 
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel.tv_caption'] = 'Liens de traduction de Babel';
$_lang['babel.tv_description'] = 'Mis à jour par le plugin Babel. Veuillez ne pas modifier!';
$_lang['babel.create_translation'] = 'Créer une traduction';
$_lang['babel.unlink_translation'] = 'Unlink translation';
$_lang['babel.link_translation_manually'] = 'Link translation manually:';
$_lang['babel.save'] = 'Save';
$_lang['babel.translation_pending'] = '[traduction en attente]';

/* language names */
$_lang['babel.language_de'] = 'Allemand';
$_lang['babel.language_en'] = 'Anglais';
$_lang['babel.language_es'] = 'Espagnol';
$_lang['babel.language_fr'] = 'Français';
$_lang['babel.language_nl'] = 'Hollandais';
$_lang['babel.language_ru'] = 'Russe';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] is no valid context key.';
$_lang['error.invalid_resource_id'] = '[[+resource]] is no valid resource id.';
$_lang['error.resource_from_other_context'] = 'Resource [[+resource]] does not exist in context [[+context]].';
$_lang['error.resource_already_linked'] = 'Resource [[+resource]] is already linked with other resources.';
$_lang['error.no_link_to_context'] = 'There does not exist any link to context [[+context]].';
$_lang['error.unlink_of_selflink_not_possible'] = 'A link to a resource itself can not be unlinked.';
$_lang['error.translation_in_same_context'] = 'A translation can not be created within the same context.';
$_lang['error.translation_already_exists'] = 'There is already a translation in context [[+context]].';
$_lang['error.could_not_create_translation'] = 'An error occured while trying to create a translation in context [[+context]].';