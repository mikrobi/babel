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
 * @language pt (Translated by João Nogueira - JANogueira)
 *
 * @todo complete babel.language_xx entries for every language
 */

$_lang['babel'] = 'Babel';
$_lang['babel.desc'] = 'Gerir sistema multilingue';
$_lang['babel.tv_caption'] = 'Ligações de Tradução do Babel';
$_lang['babel.tv_description'] = 'Gerido pelo plugin Babel. Por favor, não modificar!';
$_lang['babel.create_translation'] = 'Criar tradução';
$_lang['babel.create_translation_confirm'] = 'Tem a certeza de que deseja criar uma nova tradução para esta página (recurso) para o contexto <b>"[[+context]]"</b>?';
$_lang['babel.unlink'] = 'Separar';
$_lang['babel.unlink_translation'] = 'Separar tradução';
$_lang['babel.unlink_translation_confirm'] = 'Tem a certeza de que deseja separar a tradução para esta página (recurso) para o contexto <b>"[[+context]]"</b>?';
$_lang['babel.unlink_all_translations'] = 'Separar todas as traduções';
$_lang['babel.unlink_all_translations_confirm'] = 'Tem a certeza de que deseja separar todas as traduções para esta página (recurso)?';
$_lang['babel.link_translation_manually'] = 'ou <strong>ligar tradução manualmente</strong>:';
$_lang['babel.link_translation'] = 'Ligar a página (recurso) existente';
$_lang['babel.id_of_target'] = 'ID do destino:';
$_lang['babel....or'] = '... ou';
$_lang['babel.pagetitle_of_target'] = 'Título da página do destino:';
$_lang['babel.copy_tv_values'] = 'Copiar TVs sincronizados para o destino';
$_lang['babel.save'] = 'Guardar';
$_lang['babel.translation_pending'] = '[traduções pendentes]';
$_lang['babel.open'] = 'Abrir';
$_lang['babel.please_wait'] = 'Por favor, aguarde...';
$_lang['babel.sync_linked_tranlations'] = 'Sincronizar todas as traduções a partir do destino';
$_lang['babel.select_tree_node'] = 'Selecione um elemento da árvore de páginas (recursos)';
$_lang['babel.all'] = 'Tudo';

/**
 * DEPRECATED
 */
/* language names */
$_lang['babel.language_ar'] = 'Árabe';
$_lang['babel.language_bg'] = 'Búlgaro';
$_lang['babel.language_ca'] = 'Catalão';
$_lang['babel.language_cs'] = 'Checo';
$_lang['babel.language_da'] = 'Dinamarquês';
$_lang['babel.language_de'] = 'Alemão';
$_lang['babel.language_en'] = 'Inglês';
$_lang['babel.language_es'] = 'Espanhol';
$_lang['babel.language_fa'] = 'Persa';
$_lang['babel.language_fi'] = 'Finlandês';
$_lang['babel.language_fr'] = 'Francês';
$_lang['babel.language_he'] = 'Hebraico';
$_lang['babel.language_hu'] = 'Húngaro';
$_lang['babel.language_id'] = 'Indonésio';
$_lang['babel.language_it'] = 'Italiano';
$_lang['babel.language_ja'] = 'Japonês';
$_lang['babel.language_ko'] = 'Coreano';
$_lang['babel.language_lt'] = 'Lituano';
$_lang['babel.language_ms'] = 'Malaio';
$_lang['babel.language_nl'] = 'Holandês';
$_lang['babel.language_no'] = 'Norueguês (Bokmål)';
$_lang['babel.language_pl'] = 'Polaco';
$_lang['babel.language_pt'] = 'Português';
$_lang['babel.language_ro'] = 'Romeno';
$_lang['babel.language_ru'] = 'Russo';
$_lang['babel.language_sk'] = 'Eslovaco';
$_lang['babel.language_sl'] = 'Esloveno';
$_lang['babel.language_sr'] = 'Sérvio';
$_lang['babel.language_sv'] = 'Sueco';
$_lang['babel.language_tr'] = 'Turco';
$_lang['babel.language_uk'] = 'Ucraniano';
$_lang['babel.language_vi'] = 'Vietnamita';
$_lang['babel.language_zh'] = 'Chinês';

/* error messages */
$_lang['error.invalid_context_key'] = '[[+context]] não é uma chave de contexto válida.';
$_lang['error.invalid_resource_id'] = '[[+resource]] não é um ID de página (recurso) válida.';
$_lang['error.resource_from_other_context'] = 'A Página (recurso) [[+resource]] não existe no contexto <b>"[[+context]]"</b>.';
$_lang['error.resource_already_linked'] = 'A Página (recurso) [[+resource]] já está ligada a outras páginas.';
$_lang['error.no_link_to_context'] = 'Não existe nenhuma ligação para o contexto [[+context]].';
$_lang['error.link_of_selflink_not_possible'] = 'Uma ligação para a própria página (recurso) não pode ser criada.';
$_lang['error.unlink_of_selflink_not_possible'] = 'Uma ligação para a própria página (recurso) não pode ser eliminada.';
$_lang['error.translation_in_same_context'] = 'Uma tradução não pode ser criada dentro do mesmo contexto.';
$_lang['error.translation_already_exists'] = 'Já existe uma tradução no contexto <b>"[[+context]]"</b>: <b>"[[+pagetitle]] ([[+resource]])"</b>';
$_lang['error.could_not_create_translation'] = 'Ocorreu um erro ao tentar criar uma tradução no contexto [[+context]].';

$_lang['babel.context_err_ns'] = 'Não foi especificado um contexto.';

$_lang['setting_babel.contextKeys'] = 'Chaves de Contextos';
$_lang['setting_babel.contextKeys_desc'] = 'Lista separada por vírgulas de chaves de contextos que devem ser utilizadas para ligar páginas (recursos) multilíngues.';
$_lang['setting_babel.babelTvName'] = 'Nome do TV do Babel';
$_lang['setting_babel.babelTvName_desc'] = 'Nome do TV onde o Babel irá guardar as ligações entre páginas (recursos) multilíngue. Este TV será gerido pelo Babel.';
$_lang['setting_babel.syncTvs'] = 'TVs a serem sincronizados';
$_lang['setting_babel.syncTvs_desc'] = 'Lista separada por vírgulas de IDs de TVs que deverão ser sincronizados pelo Babel.';
