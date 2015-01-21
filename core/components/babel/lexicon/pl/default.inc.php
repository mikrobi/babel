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
 * Babel Polish language file
 * 
 * @author Jakub Kalina <modx@jakubkalina.pl>
 *
 * @package babel
 * @subpackage lexicon
 *
 */

$_lang['babel.tv_caption'] = 'Łącza do tłumaczeń Babel';
$_lang['babel.tv_description'] = 'Zarządzane przez wtyczkę Babel. Proszę nie modyfikować!';
$_lang['babel.create_translation'] = 'Utwórz tłumaczenie';
$_lang['babel.create_translation_confirm'] = 'Czy jesteś pewien, że chcesz stworzyć nowe tłumaczenie tego zasobu dla kontekstu <b>"[[+context]]"</b>?';
$_lang['babel.unlink'] = 'Odłącz';
$_lang['babel.unlink_translation'] = 'Odłącz tłumaczenie';
$_lang['babel.unlink_translation_confirm'] = 'Czy jesteś pewien, że chcesz odłączyć tłumaczenie tego zasobu dla kontekstu <b>"[[+context]]"</b>?';
$_lang['babel.link_translation_manually'] = 'lub <strong>połącz tłumaczenia ręcznie</strong>:';
$_lang['babel.link_translation'] = 'Połącz z istniejącym zasobem';
$_lang['babel.id_of_target'] = 'ID zasobu docelowego:';
$_lang['babel.copy_tv_values'] = 'Skopiuj wartości synchronizowanych zmiennych szablonu (TV) do zasobu docelowego';
$_lang['babel.save'] = 'Zapisz';
$_lang['babel.translation_pending'] = '[wymaga przetłumaczenia]';
$_lang['babel.open'] = 'Otwórz';
$_lang['babel.please_wait'] = 'Proszę czekać...';

/* language names */
$_lang['babel.language_ar'] = 'Arabski';
$_lang['babel.language_bg'] = 'Bułgarski';
$_lang['babel.language_ca'] = 'Kataloński';
$_lang['babel.language_cs'] = 'Czeski';
$_lang['babel.language_da'] = 'Duński';
$_lang['babel.language_de'] = 'Niemiecki';
$_lang['babel.language_en'] = 'Angielski';
$_lang['babel.language_es'] = 'Hiszpański';
$_lang['babel.language_fa'] = 'Perski';
$_lang['babel.language_fi'] = 'Fiński';
$_lang['babel.language_fr'] = 'Francuski';
$_lang['babel.language_he'] = 'Hebrajski';
$_lang['babel.language_hu'] = 'Węgierski';
$_lang['babel.language_id'] = 'Indonezyjski';
$_lang['babel.language_it'] = 'Włoski';
$_lang['babel.language_ja'] = 'Japoński';
$_lang['babel.language_ko'] = 'Koreański';
$_lang['babel.language_lt'] = 'Litewski';
$_lang['babel.language_ms'] = 'Malajski';
$_lang['babel.language_nl'] = 'Holenderski';
$_lang['babel.language_no'] = 'Norweski (Bokmål)';
$_lang['babel.language_pl'] = 'Polski';
$_lang['babel.language_pt'] = 'Portugalski';
$_lang['babel.language_ro'] = 'Rumuński';
$_lang['babel.language_ru'] = 'Rosyjski';
$_lang['babel.language_sk'] = 'Słowacki';
$_lang['babel.language_sl'] = 'Słoweński';
$_lang['babel.language_sr'] = 'Serbski';
$_lang['babel.language_sv'] = 'Szwedzki';
$_lang['babel.language_tr'] = 'Turecki';
$_lang['babel.language_uk'] = 'Ukraiński';
$_lang['babel.language_vi'] = 'Wietnamski';
$_lang['babel.language_zh'] = 'Chiński';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] nie jest poprawnym kluczem kontekstu.';
$_lang['error.invalid_resource_id'] = '[[+resource]] nie jest poprawnym ID zasobu.';
$_lang['error.resource_from_other_context'] = 'Zasób [[+resource]] nie istnieje dla kontekstu <b>"[[+context]]"</b>.';
$_lang['error.resource_already_linked'] = 'Zasób [[+resource]] jest już połączony z innymi zasobami.';
$_lang['error.no_link_to_context'] = 'Nie istnieje łącze dla kontekstu [[+context]].';
$_lang['error.link_of_selflink_not_possible'] = 'Zasób nie może był połączony ze samym sobą.';
$_lang['error.unlink_of_selflink_not_possible'] = 'Zasób nie może być odłączony od samego siebie.';
$_lang['error.translation_in_same_context'] = 'Tłumaczenie nie może być tworzone dla tego samego kontekstu.';
$_lang['error.translation_already_exists'] = 'W kontekście <b>"[[+context]]"</b> istnieje już tłumaczenie: <b>"[[+pagetitle]] ([[+resource]])"</b>';
$_lang['error.could_not_create_translation'] = 'Wystąpił błąd podczas próby utworzenia tłumaczenia dla kontekstu [[+context]].';

$_lang['babel.context_err_ns'] = 'Nie zdefiniowano kontekstu.';