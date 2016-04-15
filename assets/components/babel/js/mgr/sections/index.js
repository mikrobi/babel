Ext.onReady(function () {
    MODx.load({xtype: 'babel-page-home'});
});
Babel.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
                xtype: 'babel-panel-home'
                , renderTo: 'babel-panel-home-div'
            }]
    });
    Babel.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(Babel.page.Home, MODx.Component);
Ext.reg('babel-page-home', Babel.page.Home);