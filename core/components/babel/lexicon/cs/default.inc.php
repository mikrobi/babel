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
 * Babel Czech language file
 * 
 * @author Pavel Železný <info@pavelzelezny.cz>
 *
 * @package babel
 * @subpackage lexicon
 * 
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel.tv_caption'] = 'Překladové odkazy Babel Translation';
$_lang['babel.tv_description'] = 'Spravováno pluginem Babel. Prosím neměnit!';
$_lang['babel.create_translation'] = 'Vytvořit překlad';
$_lang['babel.unlink_translation'] = 'Odpojit překlad';
$_lang['babel.link_translation_manually'] = 'nebo <strong>přidělit odkaz ručně</strong>:';
$_lang['babel.id_of_target'] = 'ID cílového dokumentu:';
$_lang['babel.copy_tv_values'] = 'Zkopírovat synchronizované TV do cílového dokumentu';
$_lang['babel.save'] = 'Uložit';
$_lang['babel.translation_pending'] = '[čeká se na překlad]';

/* language names */
$_lang['babel.language_ar'] = 'Arabština';
$_lang['babel.language_bg'] = 'Bulharština';
$_lang['babel.language_ca'] = 'Catalánština';
$_lang['babel.language_cs'] = 'Čeština';
$_lang['babel.language_da'] = 'Dánština';
$_lang['babel.language_de'] = 'Němčina';
$_lang['babel.language_en'] = 'Angličtina';
$_lang['babel.language_es'] = 'Španělština';
$_lang['babel.language_fa'] = 'Perština';
$_lang['babel.language_fi'] = 'Finština';
$_lang['babel.language_fr'] = 'Francouzština';
$_lang['babel.language_he'] = 'Hebrejština';
$_lang['babel.language_hu'] = 'Maďarština';
$_lang['babel.language_id'] = 'Indonezština';
$_lang['babel.language_it'] = 'Italština';
$_lang['babel.language_ja'] = 'Japonština';
$_lang['babel.language_ko'] = 'Korejština';
$_lang['babel.language_lt'] = 'Litevština';
$_lang['babel.language_ms'] = 'Malajština';
$_lang['babel.language_nl'] = 'Holandština';
$_lang['babel.language_no'] = 'Norština (Bokmål)';
$_lang['babel.language_pl'] = 'Polština';
$_lang['babel.language_pt'] = 'Portugalština';
$_lang['babel.language_ro'] = 'Rumunština';
$_lang['babel.language_ru'] = 'Ruština';
$_lang['babel.language_sk'] = 'Slovenština';
$_lang['babel.language_sl'] = 'Slovinština';
$_lang['babel.language_sr'] = 'Serbština';
$_lang['babel.language_sv'] = 'Švédština';
$_lang['babel.language_tr'] = 'Turuečtina';
$_lang['babel.language_uk'] = 'Ukrainština';
$_lang['babel.language_vi'] = 'Vietnamština';
$_lang['babel.language_zh'] = 'Čínština';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] není platný klíč kontextu';
$_lang['error.invalid_resource_id'] = '[[+resource]] není platné id dokumentu.';
$_lang['error.resource_from_other_context'] = 'Dokument [[+resource]] neexistuje v kontextu [[+context]].';
$_lang['error.resource_already_linked'] = 'Dokument [[+resource]] je již propojen s jiným dokumentem.';
$_lang['error.no_link_to_context'] = 'Neexistuje odkaz na kontext [[+context]].';
$_lang['error.unlink_of_selflink_not_possible'] = 'Odkaz na sebe sama nemůže být odebrán.';
$_lang['error.translation_in_same_context'] = 'Překlad nemůže být vytvořen ve stejném kontextu.';
$_lang['error.translation_already_exists'] = 'Překlad v kontextu [[+context]] již existuje.';
$_lang['error.could_not_create_translation'] = 'Nastala chyba při vytváření překladu v kontextu [[+context]].';