Babel.combo.Context = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        url: Babel.config.connectorUrl,
        baseParams: {
            action: 'mgr/context/getlist',
            combo: true,
            exclude: 'mgr'
        }
    });
    Babel.combo.Context.superclass.constructor.call(this, config);

    this.on('select', function (comp, record, index){
        if (comp.getValue() === "" || comp.getValue() === "&nbsp;") {
            comp.setValue(null);
        }
    });
    this.on('change', function (comp, newValue, oldValue){
        if (newValue === "" || newValue === "&nbsp;") {
            comp.setValue(null);
        }
    });
};
Ext.extend(Babel.combo.Context, MODx.combo.Context);
Ext.reg('babel-combo-context', Babel.combo.Context);
