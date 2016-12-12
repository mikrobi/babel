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
 * Babel English language file
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *         goldsky <goldsky@virtudraft.com>
 *
 * @package babel
 * @subpackage lexicon
 *
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel'] = 'Babel';
$_lang['babel.desc'] = 'Managing multilingual system';
$_lang['babel.tv_caption'] = 'Babel Translation Links';
$_lang['babel.tv_description'] = 'Maintained by Babel plugin. Please do not change!';
$_lang['babel.create_translation'] = 'Create translation';
$_lang['babel.create_translation_confirm'] = 'Are you sure you want to create new translation for this resource to <b>"[[+context]]"</b> context?';
$_lang['babel.unlink'] = 'Unlink';
$_lang['babel.unlink_translation'] = 'Unlink translation';
$_lang['babel.unlink_translation_confirm'] = 'Are you sure you want to unlink translation from this resource to <b>"[[+context]]"</b> context?';
$_lang['babel.unlink_all_translations'] = 'Unlink all translations';
$_lang['babel.unlink_all_translations_confirm'] = 'Are you sure you want to unlink all translations from this resource?';
$_lang['babel.link_translation_manually'] = 'or <strong>link translation manually</strong>:';
$_lang['babel.link_translation'] = 'Link to existing resource';
$_lang['babel.id_of_target'] = 'ID of target:';
$_lang['babel....or'] = '... or';
$_lang['babel.pagetitle_of_target'] = 'Pagetitle of target:';
$_lang['babel.copy_tv_values'] = 'Copy synchronized TVs to target';
$_lang['babel.save'] = 'Save';
$_lang['babel.translation_pending'] = '[translations pending]';
$_lang['babel.open'] = 'Open';
$_lang['babel.please_wait'] = 'Please wait...';
$_lang['babel.sync_linked_tranlations'] = 'Synchronize all translations from the target';
$_lang['babel.select_tree_node'] = 'Select a node of the resource tree';
$_lang['babel.all'] = 'All';

/**
 * DEPRECATED
 */
/* language names */
$_lang['babel.language_ar'] = 'Arabic';
$_lang['babel.language_bg'] = 'Bulgarian';
$_lang['babel.language_ca'] = 'Catalan';
$_lang['babel.language_cs'] = 'Czech';
$_lang['babel.language_da'] = 'Danish';
$_lang['babel.language_de'] = 'German';
$_lang['babel.language_en'] = 'English';
$_lang['babel.language_es'] = 'Spanish';
$_lang['babel.language_fa'] = 'Persian';
$_lang['babel.language_fi'] = 'Finnish';
$_lang['babel.language_fr'] = 'French';
$_lang['babel.language_he'] = 'Hebrew';
$_lang['babel.language_hu'] = 'Hungarian';
$_lang['babel.language_id'] = 'Indonesian';
$_lang['babel.language_it'] = 'Italian';
$_lang['babel.language_ja'] = 'Japanese';
$_lang['babel.language_ko'] = 'Korean';
$_lang['babel.language_lt'] = 'Lithuanian';
$_lang['babel.language_ms'] = 'Malay';
$_lang['babel.language_nl'] = 'Dutch';
$_lang['babel.language_no'] = 'Norwegian (Bokmål)';
$_lang['babel.language_pl'] = 'Polish';
$_lang['babel.language_pt'] = 'Portuguese';
$_lang['babel.language_ro'] = 'Romanian';
$_lang['babel.language_ru'] = 'Russian';
$_lang['babel.language_sk'] = 'Slovak';
$_lang['babel.language_sl'] = 'Slovenian';
$_lang['babel.language_sr'] = 'Serbian';
$_lang['babel.language_sv'] = 'Swedish';
$_lang['babel.language_tr'] = 'Turkish';
$_lang['babel.language_uk'] = 'Ukrainian';
$_lang['babel.language_vi'] = 'Vietnamese';
$_lang['babel.language_zh'] = 'Chinese';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] is no valid context key.';
$_lang['error.invalid_resource_id'] = '[[+resource]] is no valid resource id.';
$_lang['error.resource_from_other_context'] = 'Resource [[+resource]] does not exist in context <b>"[[+context]]"</b>.';
$_lang['error.resource_already_linked'] = 'Resource [[+resource]] is already linked with other resources.';
$_lang['error.no_link_to_context'] = 'There does not exist any link to context [[+context]].';
$_lang['error.link_of_selflink_not_possible'] = 'A link to a resource itself can not be linked.';
$_lang['error.unlink_of_selflink_not_possible'] = 'A link to a resource itself can not be unlinked.';
$_lang['error.translation_in_same_context'] = 'A translation can not be created within the same context.';
$_lang['error.translation_already_exists'] = 'There is already a translation in context <b>"[[+context]]"</b>: <b>"[[+pagetitle]] ([[+resource]])"</b>';
$_lang['error.could_not_create_translation'] = 'An error occured while trying to create a translation in context [[+context]].';

$_lang['babel.context_err_ns'] = 'Context was not specified.';

$_lang['setting_babel.contextKeys'] = 'Context Keys';
$_lang['setting_babel.contextKeys_desc'] = 'Comma separated list of context keys which should be used to link multilingual resources.';
$_lang['setting_babel.babelTvName'] = 'Babel TV Name';
$_lang['setting_babel.babelTvName_desc'] = 'Name of template variable (TV) in which Babel will store the links between multilingual resources this TV will be maintained by Babel.';
$_lang['setting_babel.syncTvs'] = 'TVs to be synchronized';
$_lang['setting_babel.syncTvs_desc'] = 'Comma separated list of ids of template variables (TVs) which should be synchronized by Babel.';