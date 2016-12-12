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
    var _this = this;
    var actionButtons = Ext.getCmp("modx-action-buttons");
    if (actionButtons) {
        var menu = [], i = 0;
        for (var ctx in menus) {
            if (ctx === _this.config.context_key) {
                continue;
            }
            if (typeof(menus[ctx]["resourceUrl"]) !== 'undefined' &&
                    menus[ctx]["resourceUrl"] !== "" &&
                    menus[ctx]["resourceUrl"] !== "#" ) {
                menu.push({
                    text: menus[ctx]["displayText"],
                    iconCls: 'icon-link',
                    menu: {
                        items: [
                            {
                                text: _('babel.open') + " <b>" + menus[ctx]["resourceTitle"] + " (" + menus[ctx]["resourceId"] + ")</b>",
                                iconCls: 'icon-page-go',
                                resourceUrl: menus[ctx]["resourceUrl"],
                                resourceId: menus[ctx]["resourceId"],
                                handler: function() {
                                    MODx.loadPage(MODx.action['resource/update'], 'id=' + this.resourceId);
                                }
                            }, '-', {
                                text: _('babel.unlink') + " <b>" + menus[ctx]["resourceTitle"] + " (" + menus[ctx]["resourceId"] + ")</b>",
                                iconCls: 'icon-unlink',
                                contextKey: ctx,
                                target: menus[ctx]["resourceId"],
                                handler: function() {
                                    _this.unlinkTranslation(this.contextKey, this.target);
                                }
                            }
                        ]
                    }
                });

                ++i;
            } else {
                menu.push({
                    text: menus[ctx]["displayText"],
                    handler: Ext.emptyFn,
                    menu: {
                        items: [
                            {
                                text: _('babel.create_translation'),
                                iconCls: 'icon-pencil-go',
                                contextKey: ctx,
                                handler: function() {
                                    _this.createTranslation(this.contextKey);
                                }
                            }, '-', {
                                text: _('babel.link_translation'),
                                iconCls: 'icon-link',
                                contextKey: ctx,
                                handler: function() {
                                    _this.linkTranslation(this.contextKey);
                                }
                            }
                        ]
                    }
                });
            }
        }
        if (i > 0) {
            menu.push('-');
            menu.push({
                text: _('babel.unlink_all_translations'),
                handler: function() {
                    _this.unlinkTranslation();
                }
            });
        }
        // destroy existing
        var buttonMenu = Ext.getCmp('babel-language-select');
        if (buttonMenu) {
            buttonMenu.destroy();
        } else {
            actionButtons.insertButton(0, ["-"]);
        }
        buttonMenu = new Ext.Button({
            id: 'babel-language-select',
            text: 'Select Language',
            menu: menu,
            listeners: {
                render: {
                    fn: function (btn) {
                        btn.setText(menus[_this.config.context_key]["displayText"]);
                    },
                    scope: this
                },
                mouseover: function (btn) {
                    btn.showMenu();
                },
                mouseout: function (btn) {
//                    btn.hideMenu();
                }
            }
        });
        actionButtons.insertButton(0, [buttonMenu]);
        actionButtons.doLayout();
    }
};

Babel.prototype.linkTranslation = function (ctx) {
    var id = MODx.request.id;
    var win = MODx.load({
        xtype: 'modx-window',
        title: _('babel.link_translation'),
        url: this.config.connectorUrl,
        baseParams: {
            action: 'mgr/resource/link',
            context: ctx,
            id: id
        },
        listeners: {
            success: {
                fn: function (r) {
                    MODx.msg.status({
                        title: _('success'),
                        message: r.message || _('save_successful')
                    });
                    this.getMenu(r.a.result.object.menu);
                    this.hideMask();
                },
                scope: this
            },
            failure: {
                fn: function (r) {
                    this.hideMask();
                },
                scope: this
            },
            beforeSubmit: {
                fn: function (r) {
                    this.loadMask();
                },
                scope: this
            }
        },
        fields: [
            {
                xtype: 'textfield',
                fieldLabel: _('context'),
                disabled: true,
                emptyText: ctx
            }, {
                xtype: 'modx-field-parent-change',
                fieldLabel: _('babel.select_tree_node'),
                id: '',
                anchor: '100%',
                name: 'target-combo',
                end: function (p) {
                    var t = Ext.getCmp('modx-resource-tree');
                    if (!t)
                        return;
                    p.d = p.d || p.v;
                    if (p.c !== ctx) {
                        return;
                    }
                    t.removeListener('click',this.handleChangeParent,this);
                    t.on('click',t._handleClick,t);
                    t.disableHref = false;
                    win.fp.getForm().findField('target').setValue(p.v);
                    win.fp.getForm().findField('page_id').setValue(null);
                    this.setValue(p.d);
                    this.oldValue = false;
                },
                handleChangeParent: function (node, e) {
                    var t = Ext.getCmp('modx-resource-tree');
                    if (!t) {
                        return false;
                    }
                    t.disableHref = true;

                    var id = node.id.split('_');
                    id = id[1];
                    if (id == this.config.currentid) {
                        MODx.msg.alert('', _('resource_err_own_parent'));
                        return false;
                    }

                    this.fireEvent('end', {
                        v: node.attributes.type !== 'modContext' ? id : node.attributes.pk,
                        d: Ext.util.Format.stripTags(node.text),
                        c: node.attributes.ctx
                    });
                    e.preventDefault();
                    e.stopEvent();
                    return true;
                }
            }, {
                xtype: 'modx-combo',
                fieldLabel: _('babel....or') + ' ' + _('babel.pagetitle_of_target'),
                name: 'page_id',
                anchor: '100%',
                url: this.config.connectorUrl,
                baseParams: {
                    action: 'mgr/resource/getlist',
                    context: ctx,
                    combo: true
                },
                displayField: 'pagetitle',
                valueField: 'id',
                fields: ['id','pagetitle'],
                editable: true,
                typeAhead: true,
                forceSelection: true,
                listeners: {
                    select: {
                        fn: function(combo, record) {
                            var val = combo.getValue();
                            if (val === "" || val === 0 || val === "&nbsp;") {
                                combo.setValue(null);
                            } else {
                                win.fp.getForm().findField('target').setValue(record.get('id'));
                            }
                            win.fp.getForm().findField('target-combo').reset();
                        },
                        scope: this
                    },
                    blur: {
                        fn: function (combo) {
                            var val = combo.getValue();
                            if (val === "" || val === 0 || val === "&nbsp;") {
                                combo.setValue(null);
                            }
                        },
                        scope: this
                    }
                },
            }, {
                fieldLabel: _('babel.id_of_target'),
                xtype: 'numberfield',
                name: 'target',
                enableKeyEvents: true,
                listeners: {
                    keyup: {
                        fn: function(field, e) {
                            win.fp.getForm().findField('target-combo').reset();
                            win.fp.getForm().findField('page_id').reset();
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('babel.copy_tv_values'),
                name: 'copy-tv-values'
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('babel.sync_linked_tranlations'),
                name: 'sync-linked-tranlations',
                checked: true
            }
        ]
    });
//    win.reset();
    win.show();
};

Babel.prototype.unlinkTranslation = function (ctx, target) {
    this.loadMask();
    ctx = ctx || '';
    target = parseInt(target) || 0;
    var id = MODx.request.id, text = '';
    if (target === 0) {
        text = _('babel.unlink_all_translations_confirm');
    } else {
        text = _('babel.unlink_translation_confirm', {context: ctx, id: id});
    }
    return MODx.msg.confirm({
        title: _('confirm'),
        text: text,
        url: this.config.connectorUrl,
        params: {
            action: 'mgr/resource/unlink',
            id: id,
            context: ctx,
            target: target
        },
        listeners: {
            success: {
                fn: function (r) {
                    MODx.msg.status({
                        title: _('success'),
                        message: r.message || _('save_successful')
                    });
                    this.getMenu(r.object.menu);
                    this.hideMask();
                },
                scope: this
            },
            failure: {
                fn: function (r) {
                    this.hideMask();
                },
                scope: this
            },
            cancel: {
                fn: function (r) {
                    this.hideMask();
                },
                scope: this
            }
        }
    });
};

Babel.prototype.createTranslation = function (ctx) {
    this.loadMask();
    var id = MODx.request.id;
    return MODx.msg.confirm({
        title: _('confirm'),
        text: _('babel.create_translation_confirm', {context: ctx, id: id}),
        url: this.config.connectorUrl,
        params: {
            action: 'mgr/resource/duplicate',
            context_key: ctx,
            id: id
        },
        listeners: {
            success: {
                fn: function (r) {
                    this.hideMask();
                    MODx.loadPage(MODx.action['resource/update'], 'id=' + r.object.id);
                },
                scope: this
            },
            failure: {
                fn: function (r) {
                    this.hideMask();
                },
                scope: this
            },
            cancel: {
                fn: function (r) {
                    this.hideMask();
                },
                scope: this
            }
        }
    });
};

Babel.prototype.loadMask = function () {
    if (!this.overlayMask) {
        var domHandler = Ext.getBody().dom;
        this.overlayMask = new Ext.LoadMask(domHandler, {
            msg: _('babel.please_wait')
        });
    }
    this.overlayMask.show();
};

Babel.prototype.hideMask = function () {
    if (this.overlayMask) {
        this.overlayMask.hide();
    }
};
