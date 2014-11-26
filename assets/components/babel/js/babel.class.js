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
 * Babel JavaScript class file for the menu in the manager.
 * 
 * @author goldsky <goldsky@virtudraft.com>
 *
 * @package babel
 */

function Babel(config) {
    this.config = config;
}

Babel.prototype.getMenu = function (menus) {
    var actionButtons = Ext.getCmp("modx-action-buttons");
    if (actionButtons) {
        var menu = [];
        for (ctx in menus) {
            if (typeof(menus[ctx]["resourceUrl"]) !== 'undefined' &&
                    menus[ctx]["resourceUrl"] !== "" &&
                    menus[ctx]["resourceUrl"] !== "#" ) {
                if (ctx === Babel.config.context_key) {
                    continue;
                }
                menu.push({
                    text: menus[ctx]["displayText"],
                    menu: {
                        items: [
                            {
                                text: _('babel.open') + " <b>" + menus[ctx]["resourceTitle"] + " (" + menus[ctx]["resourceId"] + ")</b>",
                                resourceUrl: menus[ctx]["resourceUrl"],
                                handler: function() {
                                    window.location.href = window.location.origin + window.location.pathname + this.resourceUrl;
                                }
                            }, '-', {
                                text: _('babel.unlink') + " <b>" + menus[ctx]["resourceTitle"] + " (" + menus[ctx]["resourceId"] + ")</b>",
                                contextKey: ctx,
                                target: menus[ctx]["resourceId"],
                                handler: function() {
                                    Babel.unlinkTranslation(this.contextKey, MODx.request.id, this.target);
                                }
                            }
                        ]
                    }
                });
            } else {
                menu.push({
                    text: menus[ctx]["displayText"],
                    handler: Ext.emptyFn,
                    menu: {
                        items: [
                            {
                                text: _('babel.create_translation'),
                                contextKey: ctx,
                                handler: function() {
                                    Babel.createTranslation(this.contextKey, MODx.request.id);
                                }
                            }, '-', {
                                text: _('babel.link_translation'),
                                contextKey: ctx,
                                handler: function() {
                                    Babel.linkTranslation(this.contextKey, MODx.request.id);
                                }
                            }
                        ]
                    }
                });
            }
        }
        var buttonMenu = new Ext.Button({
            text: 'Select Language',
            menu: menu,
            listeners: {
                render: {
                    fn: function (btn) {
                        btn.setText(menus[Babel.config.context_key]["displayText"]);
                    },
                    scope: this
                }
            }
        });
        actionButtons.insertButton(0, [buttonMenu, "-"]);
        actionButtons.doLayout();
    }
};

Babel.prototype.linkTranslation = function (ctx, id) {
    var win = MODx.load({
        xtype: 'modx-window',
        title: _('babel.link_translation'),
        url: Babel.config.connector_url,
        baseParams: {
            action: 'mgr/resource/link',
            context: ctx,
            id: id
        },
        listeners: {
            'success': {
                fn: function (r) {
                    MODx.msg.status({
                        title: _('success'),
                        message: r.message || _('save_successful')
                    });
                },
                scope: this}
        },
        fields: [
            {
                xtype: 'textfield',
                fieldLabel: _('context'),
                disabled: true,
                emptyText: ctx
            }, {
                xtype: 'hidden',
                name: 'target'
            }, {
                xtype: 'modx-field-parent-change',
                fieldLabel: _('babel.id_of_target'),
                id: '',
                width: 370,
                name: 'target-combo',
                end: function (p) {
                    var t = Ext.getCmp('modx-resource-tree');
                    if (!t)
                        return;
                    p.d = p.d || p.v;
                    win.fp.getForm().findField('target').setValue(p.v);
                    this.setValue(p.d);
                    this.oldValue = false;
                }
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('babel.copy_tv_values'),
                name: 'copy-tv-values'
            }
        ]
    });
    win.reset();
    win.show();
};

Babel.prototype.unlinkTranslation = function (ctx, id, target) {
    return MODx.msg.confirm({
        title: _('confirm'),
        text: _('babel.unlink_translation_confirm', {context: ctx, id: id}),
        url: Babel.config.connector_url,
        params: {
            action: 'mgr/resource/unlink',
            context: ctx,
            id: id,
            target: target
        },
        listeners: {
            'success': {
                fn: function (r) {
                    MODx.msg.status({
                        title: _('success'),
                        message: r.message || _('save_successful')
                    });
                },
                scope: this}
        }
    });
};

Babel.prototype.createTranslation = function (ctx, id) {
    return MODx.msg.confirm({
        title: _('confirm'),
        text: _('babel.create_translation_confirm', {context: ctx, id: id}),
        url: Babel.config.connector_url,
        params: {
            action: 'mgr/resource/duplicate',
            context_key: ctx,
            id: id
        },
        listeners: {
            'success': {
                fn: function (r) {
                    MODx.loadPage('resource/update', 'id=' + r.object.id);
                },
                scope: this}
        }
    });
};