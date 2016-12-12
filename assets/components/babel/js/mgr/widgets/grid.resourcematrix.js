Babel.grid.ResourceMatrix = function (config) {
    config = config || {};

    var columns = [];
    var fields = [];
    var contexts = [];

    columns.push({
        header: _('id'),
        dataIndex: 'id',
        sortable: true,
        width: 70,
        locked: true,
        id: 'res_id'
    });
    columns.push({
        header: _('context'),
        dataIndex: 'context_key',
        sortable: true,
        width: 80,
        locked: true,
        id: 'context_key'
    });
    columns.push({
        header: _('pagetitle'),
        dataIndex: 'pagetitle',
        sortable: true,
        width: 200,
        locked: true,
        id: 'pagetitle'
    });

    columns.push({
        header: _('babel.all'),
        xtype: 'actioncolumn',
        dataIndex: 'id',
        sortable: false,
        editable: false,
        fixed: true,
        width: 50,
        locked: true,
        items: [
            {
                iconCls: 'icon-unlink icon-babel-actioncolumn-img',
                tooltip: _('babel.unlink'),
                altText: _('babel.unlink'),
                handler: function (grid, row, col) {
                    var rec = this.store.getAt(row);
                    grid.unlinkTranslation('', rec.get('id'), 0)
                },
                scope: this
            }
        ]
    });

    fields.push('id');
    fields.push('context_key');
    fields.push('pagetitle');
    fields.push('parent');

    if (config.record) {
        Ext.each(config.record, function (item, index) {
            fields.push('linkedres_id_' + item.key);
            fields.push('linkedres_pagetitle_' + item.key);
            contexts.push(item.key);

            var actionItems = [];
            actionItems.push({
                handler: function (grid, row, col) {
                    var sel_model = grid.getSelectionModel();
                    sel_model.selectRow(row);
                    var rec = grid.getStore().getAt(row);
                    var colName = grid.getColumnModel().getDataIndex(col);
                    var ctx = colName.substr('linkedres_id_'.length);
                    var cellvalue = rec.data[colName];
                    if (cellvalue.length === 0) {
                        grid.createTranslation(ctx, rec.get('id'));
                    } else {
                        MODx.loadPage(MODx.action['resource/update'], 'id=' + cellvalue);
                    }
                },
                getClass: function (v, meta, rec) {
                    if (meta.id === 'linkedres_id_' + rec.get('context_key')) {
                        return '';
                    }
                    if (rec.get(meta.id) === '') {
                        this.items[0].tooltip = _('babel.create_translation');
                        this.items[0].altText = _('babel.create_translation');
                        return 'icon-pencil-go icon-babel-actioncolumn-img';
                    } else {
                        var ctx = meta.id.substr('linkedres_id_'.length);
                        var pagetitle = rec.get('linkedres_pagetitle_' + ctx);
                        this.items[0].tooltip = _('edit') + ': ' + pagetitle;
                        this.items[0].altText = _('edit') + ': ' + pagetitle;
                        return 'icon-page-go icon-babel-actioncolumn-img';
                    }
                }
            });
            actionItems.push({
                handler: function (grid, row, col) {
                    var sel_model = grid.getSelectionModel();
                    sel_model.selectRow(row);
                    var rec = grid.getStore().getAt(row);
                    var colName = grid.getColumnModel().getDataIndex(col);
                    var ctx = colName.substr('linkedres_id_'.length);
                    var cellvalue = rec.data[colName];
                    if (cellvalue.length === 0) {
                        grid.linkTranslation(ctx, rec.get('id'));
                    } else {
                        grid.unlinkTranslation(ctx, rec.get('id'), cellvalue);
                    }
                },
                getClass: function (v, meta, rec) {
                    if (meta.id === 'linkedres_id_' + rec.get('context_key')) {
                        return '';
                    }
                    if (rec.get(meta.id) === '') {
                        this.items[1].tooltip = _('babel.link_translation');
                        this.items[1].altText = _('babel.link_translation');
                        return 'icon-link icon-babel-actioncolumn-img';
                    } else {
                        var ctx = meta.id.substr('linkedres_id_'.length);
                        var pagetitle = rec.get('linkedres_pagetitle_' + ctx);
                        this.items[1].tooltip = _('babel.unlink') + ': ' + pagetitle;
                        this.items[1].altText = _('babel.unlink') + ': ' + pagetitle;
                        return 'icon-unlink icon-babel-actioncolumn-img';
                    }
                }
            });

            columns.push({
                header: item.key,
                width: 70,
                sortable: false,
                id: 'linkedres_id_' + item.key,
                dataIndex: 'linkedres_id_' + item.key, // 'id' conflicts with Indonesian's ISO code 'id'
                xtype: 'actioncolumn',
                items: actionItems
            });
        });
    }

    var cm = new Ext.ux.grid.LockingColumnModel({
        columns: columns
    });
    var view = new Ext.ux.grid.LockingGridView({
        syncHeights: true
    });

    Ext.applyIf(config, {
        id: 'babel-grid-resourcematrix',
        url: Babel.config.connectorUrl,
        baseParams: {
            action: 'mgr/resource/getMatrixList',
            contexts: contexts.toString()
        },
        fields: fields,
        paging: true,
        remoteSort: true,
        anchor: '100%',
        colModel: cm,
        view: view,
        autoHeight: false,
        tbar: [
            {
                xtype: 'babel-combo-context',
                id: 'babel-combo-context'
            }, {
                xtype: 'textfield',
                id: 'babel-search-resource',
                width: 300,
                emptyText: _('babel.search...')
            }, {
                text: _('babel.filter'),
                handler: function () {
                    var ctx = Ext.getCmp('babel-combo-context').getValue();
                    var qry = Ext.getCmp('babel-search-resource').getValue();
                    this.baseParams.context = ctx;
                    this.baseParams.query = qry;
                    this.getBottomToolbar().changePage(1);
                    this.refresh();
                },
                scope: this
            }, {
                text: _('babel.reset'),
                handler: function () {
                    Ext.getCmp('babel-combo-context').reset();
                    Ext.getCmp('babel-combo-context').setValue(null);
                    Ext.getCmp('babel-search-resource').reset();
                    this.baseParams.context = '';
                    this.baseParams.query = '';
                    this.getBottomToolbar().changePage(1);
                    this.refresh();
                }
            }
        ]
    });

    Babel.grid.ResourceMatrix.superclass.constructor.call(this, config);

    var originalHeight = this.getHeight();
    this.store.on('load', function(store, records, options){
        // fixing height
        var scrollerHeight = this.getView().scroller.dom.children[0].offsetHeight;
        var tbarHeight = this.getTopToolbar().getHeight();
        this.setHeight(tbarHeight + originalHeight + scrollerHeight + 4);

    }, this);

};
Ext.extend(Babel.grid.ResourceMatrix, MODx.grid.Grid, {
    linkTranslation: function (ctx, id) {
        var win = MODx.load({
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
                        this.hideGridMask();
                        this.refresh();
                    },
                    scope: this
                },
                failure: {
                    fn: function (r) {
                        this.hideGridMask();
                    },
                    scope: this
                },
                beforeSubmit: {
                    fn: function (r) {
                        this.loadGridMask();
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
                        t.removeListener('click', this.handleChangeParent, this);
                        t.on('click', t._handleClick, t);
                        t.disableHref = false;

                        win.fp.getForm().findField('target').setValue(p.v);
                        win.fp.getForm().findField('page_id').setValue(null);
                        this.setValue(p.d);
                        this.oldValue = false;
                    },
                    disableTreeClick: function () {
                        MODx.debug('Disabling tree click');
                        var t = Ext.getCmp('modx-resource-tree');
                        if (!t) {
                            MODx.debug('No tree found in disableTreeClick!');
                            return false;
                        }
                        this.oldDisplayValue = this.getValue();
                        this.oldValue = win.fp.getForm().findField('target').getValue();

                        this.setValue(_('resource_parent_select_node'));

                        t.expand();
                        t.removeListener('click', t._handleClick);
                        t.on('click', this.handleChangeParent, this);
                        t.disableHref = true;

                        return true;
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
                    forceSelection: false,
                    listeners: {
                        select: {
                            fn: function (combo, record, index) {
                                var val = combo.getValue();
                                if (val === "" || val === 0 || val === "&nbsp;") {
                                    combo.setValue(null);
                                } else {
                                    win.fp.getForm().findField('target').setValue(record.id);
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
                    }
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
        win.reset();
        win.show();
    },
    unlinkTranslation: function (ctx, id, target) {
        var _this = Ext.getCmp('babel-grid-resourcematrix');
        _this.loadGridMask();
        ctx = ctx || '';
        target = parseInt(target) || 0;
        var text = '';
        if (target === 0) {
            text = _('babel.unlink_all_translations_confirm');
        } else {
            text = _('babel.unlink_translation_confirm', {context: ctx, id: id});
        }
        return MODx.msg.confirm({
            title: _('confirm'),
            text: text,
            url: Babel.config.connectorUrl,
            params: {
                action: 'mgr/resource/unlink',
                context: ctx,
                id: id,
                target: target
            },
            listeners: {
                success: {
                    fn: function (r) {
                        MODx.msg.status({
                            title: _('success'),
                            message: r.message || _('save_successful')
                        });
                        _this.hideGridMask();
                        _this.refresh();
                    }
                },
                failure: {
                    fn: function (r) {
                        _this.hideGridMask();
                    }
                },
                cancel: {
                    fn: function (r) {
                        _this.hideGridMask();
                    }
                }
            }
        });
    },
    createTranslation: function (ctx, id) {
        var _this = Ext.getCmp('babel-grid-resourcematrix');
        _this.loadGridMask();
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
                        _this.hideGridMask();
                        MODx.loadPage(MODx.action['resource/update'], 'id=' + r.object.id);
                    }
                },
                failure: {
                    fn: function (r) {
                        _this.hideGridMask();
                    }
                },
                cancel: {
                    fn: function (r) {
                        _this.hideGridMask();
                    }
                }
            }
        });
    },
    loadGridMask: function () {
        if (!this.overlayMask) {
            var domHandler = Ext.getBody().dom;
            this.overlayMask = new Ext.LoadMask(domHandler, {
                msg: _('babel.please_wait')
            });
        }
        this.overlayMask.show();
    },
    hideGridMask: function () {
        if (this.overlayMask) {
            this.overlayMask.hide();
        }
    }
});
Ext.reg('babel-grid-resourcematrix', Babel.grid.ResourceMatrix);
