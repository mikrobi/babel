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
 * Babel German language file
 *
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage lexicon
 * @language de
 *
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel'] = 'Babel';
$_lang['babel.desc'] = 'Verwaltungssystem für mehrsprachige Sites';
$_lang['babel.tv_caption'] = 'Babel-Übersetzungslinks';
$_lang['babel.tv_description'] = 'Wird vom Babel-Plugin verwaltet. Bitte nicht ändern';
$_lang['babel.create_translation'] = 'Übersetzung anlegen';
$_lang['babel.create_translation_confirm'] = 'Sind Sie sicher, dass Sie eine neue Übersetzung für diese Ressource im Kontext <b>"[[+context]]"</b> anlegen möchten?';
$_lang['babel.unlink'] = 'Verknüpfung lösen';
$_lang['babel.unlink_translation'] = 'Verknüpfung der Übersetzung lösen';
$_lang['babel.unlink_translation_confirm'] = 'Sind Sie sicher, dass Sie die Verknüpfung der Übersetzung im Kontext <b>"[[+context]]"</b> von dieser Ressource lösen möchten?';
$_lang['babel.unlink_all_translations'] = 'Verknüpfungen aller Übersetzungen lösen';
$_lang['babel.unlink_all_translations_confirm'] = 'Sind Sie sicher, dass Sie die Verknüpfungen aller Übersetzungen von dieser Ressource lösen möchten?';
$_lang['babel.link_translation_manually'] = 'oder <strong>Übersetzung manuell verknüpfen</strong>:';
$_lang['babel.link_translation'] = 'Mit einer existierenden Ressource verknüpfen';
$_lang['babel.id_of_target'] = 'Ziel-ID:';
$_lang['babel....or'] = '... oder';
$_lang['babel.pagetitle_of_target'] = 'Seitentitel des Ziels:';
$_lang['babel.copy_tv_values'] = 'Synchronisierte TVs zum Ziel kopieren';
$_lang['babel.save'] = 'Speichern';
$_lang['babel.translation_pending'] = '[Übersetzung ausstehend]';
$_lang['babel.open'] = 'Öffnen';
$_lang['babel.please_wait'] = 'Bitte warten...';
$_lang['babel.sync_linked_tranlations'] = 'Alle Übersetzungen synchronisieren';  // from the target???
$_lang['babel.select_tree_node'] = 'Wählen Sie einen Knoten des Ressourcen-Baumes';
$_lang['babel.all'] = 'Alle';

/**
 * DEPRECATED
 */
/* language names */
$_lang['babel.language_ar'] = 'Arabisch';
$_lang['babel.language_bg'] = 'Bulgarisch';
$_lang['babel.language_ca'] = 'Katalanisch';
$_lang['babel.language_cs'] = 'Tschechisch';
$_lang['babel.language_da'] = 'Dänisch';
$_lang['babel.language_de'] = 'Deutsch';
$_lang['babel.language_en'] = 'Englisch';
$_lang['babel.language_es'] = 'Spanisch';
$_lang['babel.language_fa'] = 'Persisch';
$_lang['babel.language_fi'] = 'Finnisch';
$_lang['babel.language_fr'] = 'Französisch';
$_lang['babel.language_he'] = 'Hebräisch';
$_lang['babel.language_hu'] = 'Ungarisch';
$_lang['babel.language_id'] = 'Indonesisch';
$_lang['babel.language_it'] = 'Italienisch';
$_lang['babel.language_ja'] = 'Japanisch';
$_lang['babel.language_ko'] = 'Koreanisch';
$_lang['babel.language_lt'] = 'Litauisch';
$_lang['babel.language_ms'] = 'Malaiisch';
$_lang['babel.language_nl'] = 'Niederländisch';
$_lang['babel.language_no'] = 'Norwegisch';
$_lang['babel.language_pl'] = 'Polnisch';
$_lang['babel.language_pt'] = 'Portugiesisch';
$_lang['babel.language_ro'] = 'Rumänisch';
$_lang['babel.language_ru'] = 'Russisch';
$_lang['babel.language_sk'] = 'Slowakisch';
$_lang['babel.language_sl'] = 'Slowenisch';
$_lang['babel.language_sr'] = 'Serbisch';
$_lang['babel.language_sv'] = 'Schwedisch';
$_lang['babel.language_tr'] = 'Türkisch';
$_lang['babel.language_uk'] = 'Ukrainisch';
$_lang['babel.language_vi'] = 'Vietnamesisch';
$_lang['babel.language_zh'] = 'Chinesisch';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] ist kein gültiger Kontext-Schlüssel.';
$_lang['error.invalid_resource_id'] = '[[+resource]] ist keine gültige Ressourcen-ID.';
$_lang['error.resource_from_other_context'] = 'Die Ressource [[+resource]] befindet sich nicht im Kontext [[+context]].';
$_lang['error.resource_already_linked'] = 'Die Ressource [[+resource]] ist bereits mit anderen Ressourcen verknüpft.';
$_lang['error.no_link_to_context'] = 'Für den Kontext [[+context]] existiert noch keine Verknüpfung.';
$_lang['error.link_of_selflink_not_possible'] = 'Eine Verknüpfung mit einer Ressource kann selbst nicht verknüpft werden.';
$_lang['error.unlink_of_selflink_not_possible'] = 'Die Verknüpfung einer Ressource auf sich selbst kann nicht entfernt werden.';
$_lang['error.translation_in_same_context'] = 'Eine Übersetzung kann nicht im gleichen Kontext angelegt werden.';
$_lang['error.translation_already_exists'] = 'Es existiert bereits eine Übersetzung im Kontext [[+context]].';
$_lang['error.could_not_create_translation'] = 'Beim Erstellen der Übersetzung im Kontext [[+context]] ist ein Fehler aufgetreten.';

$_lang['babel.context_err_ns'] = 'Der Kontext wurde nicht angegeben.';

$_lang['setting_babel.contextKeys'] = 'Kontext-Schlüssel';
$_lang['setting_babel.contextKeys_desc'] = 'Kommaseparierte Liste von Kontext-Schlüsseln, die verwendet werden sollen, um die Ressourcen der verschiedenen Sprachen zu verbinden.';
$_lang['setting_babel.babelTvName'] = 'Babel-TV-Name';
$_lang['setting_babel.babelTvName_desc'] = 'Name der Template-Variablen (TV), in der Babel die Links zwischen den Ressourcen der verschiedenen Sprachen speichern wird. Diese TV wird von Babel verwaltet.';
$_lang['setting_babel.syncTvs'] = 'Zu synchronisierende TVs';
$_lang['setting_babel.syncTvs_desc'] = 'Kommaseparierte Liste von IDs von Template-Variablen (TVs), die von Babel synchronisiert werden sollen.';
