Babel.panel.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        border: false,
        baseCls: 'modx-formpanel',
        cls: 'container',
        items: [
            {
                html: '<h2>' + _('babel') + '</h2>',
                border: false,
                cls: 'modx-page-header'
            }, {
                html: _('babel.icon_descriptions', {'base_url': MODx.config.base_url}),
                cls: 'panel-desc'
            }, {
                id: 'babel-grid-resourcematrix-holder',
                preventRender: true,
                border: false,
                listeners: {
                    'afterrender': {
                        fn: function (tabPanel) {
                            this.getContexts('getResourceGrid');
                        },
                        scope: this
                    }
                }
//                xtype: 'babel-grid-resourcematrix',
//                cls: 'main-wrapper',
//                preventRender: true
            }
        ]
    });
    Babel.panel.Home.superclass.constructor.call(this, config);

};
Ext.extend(Babel.panel.Home, MODx.Panel, {
    contexts: [],
    getContexts: function(callback) {
        if (this.contexts.length > 0) {
            if (typeof(this[callback]) === 'function') {
                return this[callback].call(this, this.config);
            }
            return this.contexts;
        }
        return MODx.Ajax.request({
            url: Babel.config.connectorUrl,
            params: {
                action: 'mgr/context/getlist',
                exclude: 'mgr'
            },
            listeners: {
                'success': {
                    fn: function (r) {
                        if (r.success) {
                            this.contexts = r.results;
                            if (this.contexts.length > 0 && typeof(this[callback]) === 'function') {
                                return this[callback].call(this, this.config);
                            }
                        }
                    },
                    scope: this
                }
            }
        });
    },
    getResourceGrid: function() {
        if (this.contexts.length < 1) {
            return;
        }
        MODx.load({
            xtype: 'babel-grid-resourcematrix',
            record: this.contexts,
            cls: 'main-wrapper',
            preventRender: true,
            applyTo: 'babel-grid-resourcematrix-holder'
        });
    }
});

Ext.reg('babel-panel-home', Babel.panel.Home);