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
 * Babel Russian language file
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *         goldsky <goldsky@virtudraft.com>
 *
 * @package babel
 * @subpackage lexicon
 * 
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel.tv_caption'] = 'Babel Translation Links';
$_lang['babel.tv_description'] = 'Используется плагином Babel. Не изменяйте!';
$_lang['babel.create_translation'] = 'Создать перевод';
$_lang['babel.create_translation_confirm'] = 'Вы уверены, что хотите создать перевод в контексте <b>«[[+context]]»</b> для данного ресурса?';
$_lang['babel.unlink'] = 'Отвязать';
$_lang['babel.unlink_translation'] = 'Отвязать перевод';
$_lang['babel.unlink_translation_confirm'] = 'Вы уверены, что хотите отвязать перевод в контексте <b>«[[+context]]»</b> для данного ресурса?';
$_lang['babel.link_translation_manually'] = 'or <strong>link translation manually</strong>:';
$_lang['babel.link_translation'] = 'Связать с существующим ресурсом';
$_lang['babel.id_of_target'] = 'Целевой ресурс:';
$_lang['babel.copy_tv_values'] = 'Копировать значения синхронизируемых доп. полей';
$_lang['babel.save'] = 'Сохранить';
$_lang['babel.translation_pending'] = '[ожидает перевода]';
$_lang['babel.open'] = 'Открыть';
$_lang['babel.please_wait'] = 'Пожалуйста, подождите...';

/* language names */
$_lang ['babel.language_ar'] = 'Арабский';
$_lang ['babel.language_bg'] = 'Болгарский';
$_lang ['babel.language_ca'] = 'Каталонский';
$_lang ['babel.language_cs'] = 'Чешский';
$_lang ['babel.language_da'] = 'Датский';
$_lang ['babel.language_de'] = 'Немецкий';
$_lang ['babel.language_en'] = 'Английский';
$_lang ['babel.language_es'] = 'Испанский';
$_lang ['babel.language_fa'] = 'Персидский';
$_lang ['babel.language_fi'] = 'Финский';
$_lang ['babel.language_fr'] = 'Французский';
$_lang ['babel.language_he'] = 'Иврит';
$_lang ['babel.language_hu'] = 'Венгерский';
$_lang ['babel.language_id'] = 'Индонезийский';
$_lang ['babel.language_it'] = 'Итальянский';
$_lang ['babel.language_ja'] = 'Японский';
$_lang ['babel.language_ko'] = 'Корейский';
$_lang ['babel.language_lt'] = 'Литовский';
$_lang ['babel.language_ms'] = 'Малайский';
$_lang ['babel.language_nl'] = 'Голландский';
$_lang ['babel.language_no'] = 'Норвежский (Bokmål)';
$_lang ['babel.language_pl'] = 'Польский';
$_lang ['babel.language_pt'] = 'Португальский';
$_lang ['babel.language_ro'] = 'Румынский';
$_lang ['babel.language_ru'] = 'Русский';
$_lang ['babel.language_sk'] = 'Словацкий';
$_lang ['babel.language_sl'] = 'Словенский';
$_lang ['babel.language_sr'] = 'Сербский';
$_lang ['babel.language_sv'] = 'Шведский';
$_lang ['babel.language_tr'] = 'Турецкий';
$_lang ['babel.language_uk'] = 'Украинский';
$_lang ['babel.language_vi'] = 'Вьетнамский';
$_lang ['babel.language_zh'] = 'Китайский';

/* error messages */
$_lang['error.invalid_context_key'] = 'Контекст «[[+context]]» не найден.';
$_lang['error.invalid_resource_id'] = 'Ресурс с идентификатором «[[+resource]]» не найден.';
$_lang['error.resource_from_other_context'] = 'Ресурс [[+resource]] не сущестует в контексте .';
$_lang['error.resource_already_linked'] = 'Ресурс [[+resource]] уже связан с другими ресурсами.';
$_lang['error.no_link_to_context'] = 'В контексте <b>«[[+context]]»</b> нет связанных ресурсов.';
$_lang['error.link_of_selflink_not_possible'] = 'Ресурс не может быть связан с самим собой.';
$_lang['error.unlink_of_selflink_not_possible'] = 'Ресурс не может быть отвязан от самого себя.';
$_lang['error.translation_in_same_context'] = 'Перевод не может быть создан в том же контексте.';
$_lang['error.translation_already_exists'] = 'Перевод в контексте <b>«[[+context]]»</b> уже есть: <b>«[[+pagetitle]] ([[+resource]])»</b>';
$_lang['error.could_not_create_translation'] = 'Произошла ошибка при попытке создать перевод в контексте [[+context]].';

$_lang['babel.context_err_ns'] = 'Не указан контекст.';