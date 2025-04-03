MODx.config.help_url = 'https://mikrobi.github.io/babel/usage/';

Babel.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        buttons: [{
            text: _('babel.about'),
            handler: Babel.aboutWindow
        }, {
            text: _('help_ex'),
            handler: MODx.loadHelpPane
        }],
        formpanel: 'babel-panel-home',
        components: [{
            xtype: 'babel-panel-home'
        }]
    });
    Babel.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(Babel.page.Home, MODx.Component);
Ext.reg('babel-page-home', Babel.page.Home);
