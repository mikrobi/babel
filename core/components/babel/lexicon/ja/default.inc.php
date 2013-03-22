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
 * Babel Japanese language file
 * 
 * @author Jakob Class <jakob.class@class-zec.de>
 *
 * @package babel
 * @subpackage lexicon
 * 
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel.tv_caption'] = 'Babel翻訳リンク';
$_lang['babel.tv_description'] = 'Babelプラグインによって維持されています。変更しないでください！';
$_lang['babel.create_translation'] = '翻訳ページを作成';
$_lang['babel.unlink_translation'] = '翻訳先とのリンクを解除';
$_lang['babel.link_translation_manually'] = 'すでに翻訳されているページにリンクさせます:';
$_lang['babel.id_of_target'] = '翻訳先のID番号:';
$_lang['babel.copy_tv_values'] = '翻訳元のテンプレート変数と同期する';
$_lang['babel.save'] = '保存';
$_lang['babel.translation_pending'] = '[翻訳中]';

/* language names */
$_lang['babel.language_ar'] = 'アラビア';
$_lang['babel.language_bg'] = 'ブルガリア';
$_lang['babel.language_ca'] = 'カタロニア';
$_lang['babel.language_cs'] = 'チェコ';
$_lang['babel.language_da'] = 'デンマーク';
$_lang['babel.language_de'] = 'ドイツ';
$_lang['babel.language_en'] = '英語';
$_lang['babel.language_es'] = 'スペイン';
$_lang['babel.language_fa'] = 'ペルシャ';
$_lang['babel.language_fi'] = 'フィンランド';
$_lang['babel.language_fr'] = 'フランス';
$_lang['babel.language_he'] = 'ヘブライ';
$_lang['babel.language_hu'] = 'ハンガリー';
$_lang['babel.language_id'] = 'インドネシア';
$_lang['babel.language_it'] = 'イタリア';
$_lang['babel.language_ja'] = '日本';
$_lang['babel.language_ko'] = '韓国';
$_lang['babel.language_lt'] = 'リトアニア';
$_lang['babel.language_ms'] = 'マレー';
$_lang['babel.language_nl'] = 'オランダ';
$_lang['babel.language_no'] = 'ノルウェー (ブークモール)';
$_lang['babel.language_pl'] = 'ポーランド';
$_lang['babel.language_pt'] = 'ポルトガル';
$_lang['babel.language_ro'] = 'ルーマニア';
$_lang['babel.language_ru'] = 'ロシア';
$_lang['babel.language_sk'] = 'スロバキア';
$_lang['babel.language_sl'] = 'スロベニア';
$_lang['babel.language_sr'] = 'セルビア';
$_lang['babel.language_sv'] = 'スウェーデン';
$_lang['babel.language_tr'] = 'トルコ';
$_lang['babel.language_uk'] = 'ウクライナ';
$_lang['babel.language_vi'] = 'ベトナム';
$_lang['babel.language_zh'] = '中国'; 

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] は間違ったコンテキストキーです。';
$_lang['error.invalid_resource_id'] = '[[+resource]] は間違ったリソースIDです。';
$_lang['error.resource_from_other_context'] = 'コンテキスト [[+context]] のリソース [[+resource]]は存在しません。';
$_lang['error.resource_already_linked'] = 'リソース [[+resource]] はすでに他のリソースに関連付けられています。';
$_lang['error.no_link_to_context'] = 'コンテキスト [[+context]] にリンクがありません。';
$_lang['error.unlink_of_selflink_not_possible'] = 'リソース自体へのリンクは、リンクを解除することはできません。';
$_lang['error.translation_in_same_context'] = '同じコンテキスト内で翻訳を作成することはできません。';
$_lang['error.translation_already_exists'] = 'コンテキスト [[+context]] はすでに関連付けられています。';
$_lang['error.could_not_create_translation'] = 'コンテキスト [[+context]] の翻訳の作成中にエラーが発生しました。';
