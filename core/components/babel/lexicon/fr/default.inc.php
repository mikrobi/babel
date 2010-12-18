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
$_lang['babel.unlink_translation'] = 'Délier la traduction';
$_lang['babel.link_translation_manually'] = 'Lier manuellement une traduction :';
$_lang['babel.save'] = 'Sauvegarder';
$_lang['babel.translation_pending'] = '[traduction en attente]';

/* language names */
$_lang['babel.language_de'] = 'Allemand';
$_lang['babel.language_en'] = 'Anglais';
$_lang['babel.language_es'] = 'Espagnol';
$_lang['babel.language_fr'] = 'Français';
$_lang['babel.language_nl'] = 'Hollandais';
$_lang['babel.language_ru'] = 'Russe';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] n\'est pas une clé de context valide.';
$_lang['error.invalid_resource_id'] = '[[+resource]] n\'est pas un id valide de ressource.';
$_lang['error.resource_from_other_context'] = 'La ressource [[+resource]] n\'existe pas dans le context [[+context]].';
$_lang['error.resource_already_linked'] = 'La ressource [[+resource]] est déjà liée à d\'autres ressources.';
$_lang['error.no_link_to_context'] = 'Il n\'existe aucun lien vers le contexte [[+context]].';
$_lang['error.unlink_of_selflink_not_possible'] = 'Un lien vers une « même ressource » ne être défait.';
$_lang['error.translation_in_same_context'] = 'Une traduction ne peut être créée au sein d\'un même contexte.';
$_lang['error.translation_already_exists'] = 'Il y a déjà une traduction dans le contexte [[+context]].';
$_lang['error.could_not_create_translation'] = 'Une erreur est survenue lors de la création de traduction dans le contexte [[+context]].';