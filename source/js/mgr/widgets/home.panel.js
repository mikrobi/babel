Babel.panel.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'container home-panel' + ((Babel.config.debug) ? ' debug' : '') + ' modx' + Babel.config.modxversion,
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: '<h2>' + _('babel') + '</h2>' + ((Babel.config.debug) ? '<div class="ribbon top-right"><span>' + _('babel.debug_mode') + '</span></div>' : ''),
            border: false,
            cls: 'modx-page-header'
        }, {
            defaults: {
                autoHeight: true
            },
            border: true,
            cls: 'babel-panel',
            items: [{
                xtype: 'babel-panel-overview'
            }]
        }]
    });
    Babel.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(Babel.panel.Home, MODx.Panel);
Ext.reg('babel-panel-home', Babel.panel.Home);

Babel.panel.HomeTab = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'babel-panel-' + config.tabtype,
        title: config.title,
        items: [{
            html: '<p>' + config.description + '</p>',
            border: false,
            cls: 'panel-desc'
        }, {
            layout: 'form',
            cls: 'x-form-label-left main-wrapper',
            defaults: {
                autoHeight: true
            },
            border: true,
            items: [{
                id: 'babel-panel-' + config.tabtype + '-' + config.contenttype,
                xtype: 'babel-' + config.contenttype + '-' + config.tabtype,
                preventRender: true,
                contexts: Babel.config.contexts,
                listeners: {
                    afterrender: function () {
                        this.store.on('load', function () {
                            var tbarHeight = this.getTopToolbar().getHeight();
                            var lockedHdHeight = this.getView().lockedHd.getHeight();
                            var lockedBodyHeight = this.getView().lockedBody.getHeight();
                            var bbarHeight = this.getBottomToolbar().getHeight();
                            this.setHeight(tbarHeight + lockedHdHeight + lockedBodyHeight + bbarHeight + 14);
                        }, this);
                    }
                }
            }]
        }],
    });
    Babel.panel.HomeTab.superclass.constructor.call(this, config);
};
Ext.extend(Babel.panel.HomeTab, MODx.Panel);
Ext.reg('babel-panel-hometab', Babel.panel.HomeTab);

Babel.panel.Overview = function (config) {
    config = config || {};
    this.ident = 'babel-overview-' + Ext.id();
    this.panelOverviewTabs = [{
        xtype: 'babel-panel-hometab',
        title: _('babel.contexts'),
        description: '<div>' + _('babel.contexts_desc') + '</div><div><i class="icon-babel-description-img icon icon-link"></i>' + _('babel.contexts_desc_link') +
            '<i class="icon-babel-description-img icon icon-chain-broken"></i>' + _('babel.contexts_desc_unlink') +
            '<i class="icon-babel-description-img icon icon-pencil-square-o"></i>' + _('babel.contexts_desc_update') +
            '<i class="icon-babel-description-img icon icon-plus-circle"></i>' + _('babel.contexts_desc_create') + '</div>',
        tabtype: 'resourcematrix',
        contenttype: 'grid'
    }];
    if (Babel.config.permissions.babel_settings || Babel.config.permissions.settings) {
        this.panelOverviewTabs.push({
            xtype: 'babel-panel-settings'
        });
    }
    Ext.applyIf(config, {
        id: this.ident,
        items: [{
            xtype: 'modx-tabs',
            border: true,
            stateful: true,
            stateId: 'babel-panel-overview',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            autoScroll: true,
            deferredRender: true,
            forceLayout: false,
            defaults: {
                layout: 'form',
                autoHeight: true,
                hideMode: 'offsets'
            },
            items: this.panelOverviewTabs,
            listeners: {
                tabchange: function (o, t) {
                    if (t.xtype === 'babel-panel-settings') {
                        if (Ext.getCmp('babel-grid-system-settings')) {
                            Ext.getCmp('babel-grid-system-settings').getStore().reload();
                        }
                    } else if (t.xtype === 'babel-panel-hometab') {
                        if (Ext.getCmp('babel-panel-' + t.tabtype + '-' + t.contenttype)) {
                            Ext.getCmp('babel-panel-' + t.tabtype + '-' + t.contenttype).getStore().reload();
                        }
                    }
                }
            }
        }]
    });
    Babel.panel.Overview.superclass.constructor.call(this, config);
};
Ext.extend(Babel.panel.Overview, MODx.Panel);
Ext.reg('babel-panel-overview', Babel.panel.Overview);
