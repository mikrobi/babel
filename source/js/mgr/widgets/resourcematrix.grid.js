Babel.grid.ResourceMatrix = function (config) {
    config = config || {};
    this.buttonColumnTpl = new Ext.XTemplate('<tpl for=".">'
        + '<tpl if="action_buttons !== null">'
        + '<ul class="action-buttons">'
        + '<tpl for="action_buttons">'
        + '<li><i class="icon {className} icon-{icon}" title="{text}" data-ctx="{ctx}" data-target="{target}"></i></li>'
        + '</tpl>'
        + '</ul>'
        + '</tpl>'
        + '</tpl>', {
        compiled: true
    });
    this.ident = 'babel-resourcematrix-' + Ext.id();
    var _this = this;
    var columns = [];
    var fields = ['id', 'context_key', 'pagetitle', 'parent'];
    var contexts = [];
    if (config.contexts) {
        columns = [{
            header: _('id'),
            width: 70,
            sortable: true,
            dataIndex: 'id',
            locked: true,
            id: 'res_id'
        }, {
            header: _('context'),
            width: 80,
            sortable: true,
            dataIndex: 'context_key',
            locked: true,
            id: 'context_key'
        }, {
            header: _('pagetitle'),
            width: 200,
            sortable: true,
            dataIndex: 'pagetitle',
            locked: true,
            id: 'pagetitle'
        }, {
            header: _('babel.all'),
            renderer: this.buttonColumnAllRenderer.bind(this),
            menuDisabled: true,
            fixed: true,
            locked: true,
            width: 50
        }];
        Ext.each(config.contexts, function (item) {
            fields.push('linkedres_id_' + item);
            fields.push('linkedres_pagetitle_' + item);
            contexts.push(item);
            columns.push({
                header: item,
                width: 70,
                sortable: false,
                dataIndex: 'linkedres_id_' + item,
                id: 'linkedres_id_' + item,
                menuDisabled: true,
                renderer: _this.buttonColumnContextRenderer.bind(_this),
            });
        });
    }
    var cm = new Ext.ux.grid.LockingColumnModel({
        columns: columns
    });
    var view = new Ext.ux.grid.LockingGridView({
        syncHeights: true
    });

    Ext.apply(config, {
        url: Babel.config.connectorUrl,
        baseParams: {
            action: 'mgr/resource/getmatrixlist',
            contexts: contexts.toString()
        },
        colModel: cm,
        fields: fields,
        paging: true,
        remoteSort: true,
        anchor: '100%',
        view: new Ext.ux.grid.LockingGridView(),
        autoHeight: false,
        showActionsColumn: false,
        tbar: [{
            xtype: 'babel-combo-context',
            id: this.ident + '-filter-context',
            emptyText: _('babel.select_context'),
            listeners: {
                select: {
                    fn: this.filterByContext,
                    scope: this
                }
            }
        }, '->', {
            xtype: 'textfield',
            id: this.ident + '-filter-query',
            cls: 'x-form-filter',
            emptyText: _('babel.search'),
            width: 300,
            listeners: {
                change: {
                    fn: this.filterByQuery,
                    scope: this
                },
                render: {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: this.blur,
                            scope: cmp
                        });
                    },
                    scope: this
                }
            }
        }, {
            xtype: 'button',
            id: this.ident + '-filter-clear',
            cls: 'x-form-filter-clear',
            text: _('babel.reset'),
            listeners: {
                click: {
                    fn: this.clearFilter,
                    scope: this
                }
            }
        }]
    });
    Babel.grid.ResourceMatrix.superclass.constructor.call(this, config);
};
Ext.extend(Babel.grid.ResourceMatrix, MODx.grid.Grid, {
    linkTranslation: function (ctx, id) {
        var window = MODx.load({
            xtype: 'modx-window',
            title: _('babel.link_translation'),
            url: Babel.config.connectorUrl,
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
                        this.hideMask();
                        this.refresh();
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
                url: Babel.config.connectorUrl,
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
        window.reset();
        window.show();
    },
    unlinkTranslation: function (ctx, id, target) {
        this.loadMask();
        ctx = ctx || '';
        target = parseInt(target) || 0;
        var text = (target === 0) ?
            _('babel.unlink_all_translations_confirm') :
            _('babel.unlink_translation_confirm', {
                context: ctx,
                id: id
            });
        return MODx.msg.confirm({
            title: _('confirm'),
            text: text,
            url: Babel.config.connectorUrl,
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
                        this.hideMask();
                        this.refresh();
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
    createTranslation: function (ctx, id) {
        this.loadMask();
        return MODx.msg.confirm({
            title: _('confirm'),
            text: _('babel.create_translation_confirm', {context: ctx, id: id}),
            url: Babel.config.connectorUrl,
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
    clearFilter: function () {
        var store = this.getStore();
        store.baseParams.context = '';
        store.baseParams.query = '';
        Ext.getCmp(this.ident + '-filter-context').reset();
        Ext.getCmp(this.ident + '-filter-query').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    filterByContext: function (cb, rec) {
        this.getStore().baseParams.context = rec.data.key;
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    filterByQuery: function (tf, newValue) {
        this.getStore().baseParams.query = newValue;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
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
    buttonColumnAllRenderer: function () {
        return this.buttonColumnTpl.apply({
            action_buttons: [{
                className: 'unlink-all',
                icon: 'chain-broken',
                text: _('babel.unlink')
            }]
        });
    },
    buttonColumnContextRenderer: function (value, metaData, record) {
        var actionButtons = [];
        if (metaData.id !== 'linkedres_id_' + record.get('context_key')) {
            var ctx = metaData.id.substr('linkedres_id_'.length);
            if (record.get(metaData.id) === '') {
                actionButtons.push({
                    className: 'create',
                    icon: 'plus-circle',
                    text: _('babel.create_translation'),
                    ctx: ctx,
                    target: 0
                }, {
                    className: 'link',
                    icon: 'link',
                    text: _('babel.link_translation'),
                    ctx: ctx,
                    target: 0
                });
            } else {
                var pagetitle = record.get('linkedres_pagetitle_' + ctx);
                var target = record.get('linkedres_id_' + ctx);
                actionButtons.push({
                    className: 'update',
                    icon: 'pencil-square-o',
                    text: _('edit') + ': ' + pagetitle + ' (' + target + ')',
                    ctx: ctx,
                    target: target
                }, {
                    className: 'unlink',
                    icon: 'chain-broken',
                    text: _('babel.unlink') + ': ' + pagetitle + ' (' + target + ')',
                    ctx: ctx,
                    target: target
                });
            }
        }
        return this.buttonColumnTpl.apply({
            action_buttons: actionButtons
        });
    },
    onClick: function (e) {
        var t = e.getTarget();
        var elm = t.className.split(' ')[0];
        if (elm === 'icon') {
            var act = t.className.split(' ')[1];
            var record = this.getSelectionModel().getSelected();
            switch (act) {
                case 'unlink-all':
                    this.unlinkTranslation('', record.get('id'), 0)
                    break;
                case 'create':
                    this.createTranslation(t.dataset.ctx, record.get('id'));
                    break;
                case 'link':
                    this.linkTranslation(t.dataset.ctx, record.get('id'));
                    break;
                case 'update':
                    MODx.loadPage('resource/update', 'id=' + t.dataset.target);
                    console.log(record);
                    break;
                case 'unlink':
                    this.unlinkTranslation(t.dataset.ctx, record.get('id'), t.dataset.target);
                    break;
                default:
                    break;
            }
        }
    }
});
Ext.reg('babel-grid-resourcematrix', Babel.grid.ResourceMatrix);
