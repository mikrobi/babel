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
 * Babel Romanian language file
 * 
 * @author Stefan Moldoveanu <stefan@moldoveanu.net>
 *
 * @package babel
 * @subpackage lexicon
 * 
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel.tv_caption'] = 'Babel - legăturile către resursele traduse';
$_lang['babel.tv_description'] = 'Menținut de pluginul Babel. Vă rugăm nu modificați!';
$_lang['babel.create_translation'] = 'Crează traducere';
$_lang['babel.unlink_translation'] = 'Anulează legătura către pagina tradusă';
$_lang['babel.link_translation_manually'] = 'sau <strong>asociază pagina tradusă manual</strong>:';
$_lang['babel.id_of_target'] = 'IDul paginii țintă:';
$_lang['babel.copy_tv_values'] = 'Copiază TV sincronizate la țintă';
$_lang['babel.save'] = 'Salvează';
$_lang['babel.translation_pending'] = '[traducere în curs]';

/* language names */
$_lang['babel.language_ar'] = 'Arabă';
$_lang['babel.language_bg'] = 'Bulgară';
$_lang['babel.language_ca'] = 'Catalană';
$_lang['babel.language_cs'] = 'Cehă';
$_lang['babel.language_da'] = 'Daneză';
$_lang['babel.language_de'] = 'Germană';
$_lang['babel.language_en'] = 'Engleză';
$_lang['babel.language_es'] = 'Spaniolă';
$_lang['babel.language_fa'] = 'Persană';
$_lang['babel.language_fi'] = 'Finlandeză';
$_lang['babel.language_fr'] = 'Franceză';
$_lang['babel.language_he'] = 'Ebraică';
$_lang['babel.language_hu'] = 'Maghiară';
$_lang['babel.language_id'] = 'Indoneziană';
$_lang['babel.language_it'] = 'Italiană';
$_lang['babel.language_ja'] = 'Japoneză';
$_lang['babel.language_ko'] = 'Coreeană';
$_lang['babel.language_lt'] = 'Lituaniană';
$_lang['babel.language_ms'] = 'Malaieză';
$_lang['babel.language_nl'] = 'Olandeză';
$_lang['babel.language_no'] = 'Norvegiană (Bokmål)';
$_lang['babel.language_pl'] = 'Poloneză';
$_lang['babel.language_pt'] = 'Portugheză';
$_lang['babel.language_ro'] = 'Română';
$_lang['babel.language_ru'] = 'Rusă';
$_lang['babel.language_sk'] = 'Slovacă';
$_lang['babel.language_sl'] = 'Slovenă';
$_lang['babel.language_sr'] = 'Sârbă';
$_lang['babel.language_sv'] = 'Suedeză';
$_lang['babel.language_tr'] = 'Turcă';
$_lang['babel.language_uk'] = 'Ucraineană';
$_lang['babel.language_vi'] = 'Vietnameză';
$_lang['babel.language_zh'] = 'Chineză';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] nu este o cheie de context validă.';
$_lang['error.invalid_resource_id'] = '[[+resource]] nu este un id vaild.';
$_lang['error.resource_from_other_context'] = 'Resursa [[+resource]] nu există în contextul [[+context]].';
$_lang['error.resource_already_linked'] = 'Resursa [[+resource]] este deja asociată cu alte resurse.';
$_lang['error.no_link_to_context'] = 'Nu există legătură către contextul [[+context]].';
$_lang['error.unlink_of_selflink_not_possible'] = 'Nu se poate asocia resursa cu ea însăși.';
$_lang['error.translation_in_same_context'] = 'Nu se poate crea o traducere în același context.';
$_lang['error.translation_already_exists'] = 'Există deja o traducere în contextul [[+context]].';
$_lang['error.could_not_create_translation'] = 'Eroare la crearea documentului de tradus în contextul [[+context]].';
