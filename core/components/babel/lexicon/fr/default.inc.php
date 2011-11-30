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
$_lang['babel.link_translation_manually'] = 'ou <strong>lier manuellement une traduction</strong>:';
$_lang['babel.id_of_target'] = 'ID of target:';
$_lang['babel.copy_tv_values'] = 'Copy synchronized TVs to target';
$_lang['babel.save'] = 'Sauvegarder';
$_lang['babel.translation_pending'] = '[traduction en attente]';

/* language names */
$_lang['babel.language_ar'] = 'Arabe';
$_lang['babel.language_bg'] = 'Bulgare';
$_lang['babel.language_ca'] = 'Catalan';
$_lang['babel.language_cs'] = 'Tchèque';
$_lang['babel.language_da'] = 'Danois';
$_lang['babel.language_de'] = 'Allemand';
$_lang['babel.language_en'] = 'Anglais';
$_lang['babel.language_es'] = 'Espagnol';
$_lang['babel.language_fa'] = 'Perse';
$_lang['babel.language_fi'] = 'Finnois';
$_lang['babel.language_fr'] = 'Français';
$_lang['babel.language_he'] = 'Hébreu';
$_lang['babel.language_hu'] = 'Hongrois';
$_lang['babel.language_id'] = 'Indonésien';
$_lang['babel.language_it'] = 'Italien';
$_lang['babel.language_ja'] = 'Japonais';
$_lang['babel.language_ko'] = 'Coréen';
$_lang['babel.language_lt'] = 'Lituanien';
$_lang['babel.language_ms'] = 'Malais';
$_lang['babel.language_nl'] = 'Néerlandais';
$_lang['babel.language_no'] = 'Norvégien';
$_lang['babel.language_pl'] = 'Polonais';
$_lang['babel.language_pt'] = 'Portugais';
$_lang['babel.language_ro'] = 'Roumain';
$_lang['babel.language_ru'] = 'Russe';
$_lang['babel.language_sk'] = 'Slovaque';
$_lang['babel.language_sl'] = 'Slovène';
$_lang['babel.language_sr'] = 'Serbe';
$_lang['babel.language_sv'] = 'Suédois';
$_lang['babel.language_tr'] = 'Turc';
$_lang['babel.language_uk'] = 'Ukrainien';
$_lang['babel.language_vi'] = 'Vietnamien';
$_lang['babel.language_zh'] = 'Chinois'; 

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