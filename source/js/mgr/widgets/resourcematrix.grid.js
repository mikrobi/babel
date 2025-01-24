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

    Ext.apply(config, {
        url: Babel.config.connectorUrl,
        baseParams: {
            action: 'mgr/resource/getmatrixlist',
            contexts: contexts.toString(),
            limit: 0,
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
        Babel.linkTranslation(ctx, id, this);
    },
    unlinkTranslation: function (ctx, id, target) {
        return Babel.unlinkTranslation(ctx, id, target, this);
    },
    createTranslation: function (ctx, id) {
        Babel.createTranslation(ctx, id)
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
    buttonColumnAllRenderer: function () {
        return this.buttonColumnTpl.apply({
            action_buttons: [{
                className: 'create-multiple',
                icon: 'plus-square',
                text: _('babel.create_multiple_translations')
            }, {
                className: 'unlink-all',
                icon: 'chain-broken',
                text: _('babel.unlink_all')
            }]
        });
    },
    buttonColumnContextRenderer: function (value, metaData, record) {
        var actionButtons = [];
        if (metaData.id !== 'linkedres_id_' + record.get('context_key')) {
            var ctx = metaData.id.substr('linkedres_id_'.length);
            var target = record.get('linkedres_id_' + ctx);
            if(target == 'x') return;
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
                case 'create-multiple':
                    this.createTranslation('', record.get('id'));
                    break;
                case 'create':
                    this.createTranslation(t.dataset.ctx, record.get('id'));
                    break;
                case 'link':
                    this.linkTranslation(t.dataset.ctx, record.get('id'));
                    break;
                case 'update':
                    MODx.loadPage('resource/update', 'id=' + t.dataset.target);
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
