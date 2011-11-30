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
 * 
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel.tv_caption'] = 'Babel-Übersetzungslinks';
$_lang['babel.tv_description'] = 'Wird über das Babel-Plugin verwaltet. Bitte nicht ändern';
$_lang['babel.create_translation'] = 'Übersetzung anlegen';
$_lang['babel.unlink_translation'] = 'Verknüpfung aufheben';
$_lang['babel.link_translation_manually'] = 'oder <strong>Übersetzung manuell verknüpfen</strong>:';
$_lang['babel.id_of_target'] = 'Ziel-ID:';
$_lang['babel.copy_tv_values'] = 'Synchronisierte TVs zum Ziel kopieren';
$_lang['babel.save'] = 'Speichern';
$_lang['babel.translation_pending'] = '[Übersetzung ausstehend]';

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
$_lang['error.resource_from_other_context'] = 'Ressource [[+resource]] befindet sich nicht im Kontext [[+context]].';
$_lang['error.resource_already_linked'] = 'Ressource [[+resource]] ist bereits mit anderen Ressourcen verknüpft.';
$_lang['error.no_link_to_context'] = 'Für den Kontext [[+context]] existiert noch keine Verknüpfung.';
$_lang['error.unlink_of_selflink_not_possible'] = 'Die Verknüpfung einer Ressource auf sich selbst kann nciht entfernt werden.';
$_lang['error.translation_in_same_context'] = 'Eine Übersetzung kann nicht im gleichen Kontext angelegt werden.';
$_lang['error.translation_already_exists'] = 'Es existiert bereits eine Übersetzung im Kontext [[+context]].';
$_lang['error.could_not_create_translation'] = 'Beim Erstellen der Übersetzung im Kontext [[+context]] ist ein Fehler aufgetreten.';