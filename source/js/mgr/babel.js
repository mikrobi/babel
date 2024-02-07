var babel = function (config) {
    config = config || {};
    babel.superclass.constructor.call(this, config);
};
Ext.extend(babel, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, util: {},
    getMenu: function (menus) {
        var _this = this;
        var actionButtons = Ext.getCmp('modx-action-buttons');
        if (actionButtons) {
            var menu = [], i = 0;
            for (var ctx in menus) {
                if (ctx === this.config.context_key) {
                    continue;
                }
                if (typeof (menus[ctx]['resourceUrl']) !== 'undefined' &&
                    menus[ctx]['resourceUrl'] !== '' &&
                    menus[ctx]['resourceUrl'] !== '#') {
                    menu.push({
                        text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-globe"></i>' + menus[ctx]['displayText'],
                        menu: {
                            items: [{
                                text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-pencil-square-o"></i>' + _('babel.open') + ' <b>' + menus[ctx]['resourceTitle'] + ' (' + menus[ctx]['resourceId'] + ')</b>',
                                resourceUrl: menus[ctx]['resourceUrl'],
                                resourceId: menus[ctx]['resourceId'],
                                handler: function () {
                                    MODx.loadPage('resource/update', 'id=' + this.resourceId);
                                }
                            }, '-', {
                                text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-chain-broken"></i>' + _('babel.unlink') + ' <b>' + menus[ctx]['resourceTitle'] + ' (' + menus[ctx]['resourceId'] + ')</b>',
                                contextKey: ctx,
                                target: menus[ctx]['resourceId'],
                                handler: function () {
                                    _this.unlinkTranslation(this.contextKey, this.target);
                                }
                            }]
                        }
                    });
                    i++;
                } else {
                    menu.push({
                        text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-globe"></i>' + menus[ctx]['displayText'],
                        handler: Ext.emptyFn,
                        menu: {
                            items: [{
                                text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-plus-circle"></i>' + _('babel.create_translation'),
                                contextKey: ctx,
                                handler: function () {
                                    _this.createTranslation(this.contextKey);
                                }
                            }, '-', {
                                text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-link"></i>' + _('babel.link_translation'),
                                contextKey: ctx,
                                handler: function () {
                                    _this.linkTranslation(this.contextKey);
                                }
                            }]
                        }
                    });
                }
            }
            if (i > 0) {
                menu.push('-');
                menu.push({
                    text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-chain-broken"></i>' + _('babel.unlink_all_translations'),
                    handler: function () {
                        _this.unlinkTranslation();
                    }
                });
            }
            menu.push('-');
            menu.push({
                text: '<i class="x-menu-item-icon x-buttonmenu-item-icon icon icon-lightbulb-o"></i>' + _('babel.about'),
                handler: function () {
                    _this.aboutWindow();
                },
            });
            // destroy existing button menu
            var buttonMenu = Ext.getCmp('babel-language-select');
            if (buttonMenu) {
                buttonMenu.destroy(); // @TODO Get the previous button position and use this for the new button
            }
            buttonMenu = new Ext.Button({
                id: 'babel-language-select',
                text: 'Select Language',
                menu: menu,
                listeners: {
                    render: {
                        fn: function (btn) {
                            btn.setText(menus[_this.config.context_key]['displayText']);
                        },
                        scope: this
                    },
                    mouseover: function (btn) {
                        btn.showMenu();
                    }
                }
            });
            actionButtons.insertButton(0, [buttonMenu]);
            actionButtons.doLayout();
        }
    },
    linkTranslation: function (ctx) {
        var id = MODx.request.id;
        var window = MODx.load({
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
                    fn: this.hideMask,
                    scope: this
                },
                beforeSubmit: {
                    fn: this.loadMask,
                    scope: this
                }
            },
            fields: [{
                xtype: 'textfield',
                fieldLabel: _('context'),
                anchor: '100%',
                disabled: true,
                emptyText: ctx
            }, {
                xtype: 'modx-field-parent-change',
                fieldLabel: _('babel.select_tree_node'),
                id: '',
                name: 'target-combo',
                anchor: '100%',
                end: function (parent) {
                    var tree = Ext.getCmp('modx-resource-tree');
                    if (!tree) {
                        return;
                    }
                    parent.display = parent.display || parent.value;
                    if (parent.context !== ctx) {
                        return;
                    }
                    tree.removeListener('click', this.handleChangeParent, this);
                    tree.on('click', tree._handleClick, tree);
                    tree.disableHref = false;
                    window.fp.getForm().findField('target').setValue(parent.value);
                    window.fp.getForm().findField('page_id').setValue(null);
                    this.setValue(parent.display);
                    this.oldValue = false;
                },
                handleChangeParent: function (node, e) {
                    var tree = Ext.getCmp('modx-resource-tree');
                    if (!tree) {
                        return false;
                    }
                    tree.disableHref = true;
                    var id = node.id.split('_');
                    id = id[1];
                    if (id === this.config.currentid) {
                        MODx.msg.alert('', _('resource_err_own_parent'));
                        return false;
                    }
                    this.fireEvent('end', {
                        value: node.attributes.type !== 'modContext' ? id : node.attributes.pk,
                        display: Ext.util.Format.stripTags(node.text),
                        context: node.attributes.ctx
                    });
                    e.preventDefault();
                    e.stopEvent();
                    return true;
                }
            }, {
                xtype: 'modx-combo',
                fieldLabel: _('babel.target_pagetitle'),
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
                fields: ['id', 'pagetitle'],
                editable: true,
                typeAhead: true,
                forceSelection: true,
                listeners: {
                    select: {
                        fn: function (combo, record) {
                            var val = combo.getValue();
                            if (val === '' || val === 0 || val === '&nbsp;') {
                                combo.setValue(null);
                            } else {
                                window.fp.getForm().findField('target').setValue(record.get('id'));
                            }
                            window.fp.getForm().findField('target-combo').reset();
                        },
                        scope: this
                    },
                    blur: {
                        fn: function (combo) {
                            var val = combo.getValue();
                            if (val === '' || val === 0 || val === '&nbsp;') {
                                combo.setValue(null);
                            }
                        },
                        scope: this
                    }
                }
            }, {
                fieldLabel: _('babel.target_id'),
                xtype: 'numberfield',
                name: 'target',
                anchor: '100%',
                enableKeyEvents: true,
                listeners: {
                    keyup: {
                        fn: function () {
                            window.fp.getForm().findField('target-combo').reset();
                            window.fp.getForm().findField('page_id').reset();
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
            }]
        });
        window.show();
    },
    unlinkTranslation: function (ctx, target) {
        this.loadMask();
        ctx = ctx || '';
        target = parseInt(target) || 0;
        var id = MODx.request.id;
        var text = (target === 0) ?
            _('babel.unlink_all_translations_confirm') :
            _('babel.unlink_translation_confirm', {
                context: ctx,
                id: id
            });
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
                    fn: this.hideMask,
                    scope: this
                },
                cancel: {
                    fn: this.hideMask,
                    scope: this
                }
            }
        });
    },
    createTranslation: function (ctx) {
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
                        MODx.loadPage('resource/update', 'id=' + r.object.id);
                    },
                    scope: this
                },
                failure: {
                    fn: this.hideMask,
                    scope: this
                },
                cancel: {
                    fn: this.hideMask,
                    scope: this
                }
            }
        });
    },
    loadMask: function () {
        if (!this.overlayMask) {
            var domHandler = Ext.getBody().dom;
            this.overlayMask = new Ext.LoadMask(domHandler, {
                msg: _('babel.please_wait')
            });
        }
        this.overlayMask.show();
    },
    hideMask: function () {
        if (this.overlayMask) {
            this.overlayMask.hide();
        }
    },
    aboutWindow: function () {
        const msg = '&copy; 2010-2024 by Jakob Class<br><br>' +
            'Authors: <a href="https://github.com/mikrobi">Jakob Class</a>, <a href="https://github.com/goldsky">Rico Goldsky</a>, <a href="https://github.com/JoshuaLuckers">Joshua Luckers</a>, <a href="https://github.com/Jako">Thomas Jakobi</a><br><br>' +
            'Repository: <a href="https://github.com/Jako/Babel">github.com/Jako/Babel</a>';
        Ext.Msg.show({
            title: _('babel') + ' ' + Babel.config.version,
            msg: msg,
            buttons: Ext.Msg.OK,
            cls: 'babel_window',
            width: 358
        });
    }
});
Ext.reg('babel', babel);

var Babel = new babel();
