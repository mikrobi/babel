Babel.combo.Context = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        url: Babel.config.connectorUrl,
        baseParams: {
            action: 'mgr/context/getlist',
            combo: true,
            exclude: 'mgr'
        },
        tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name:htmlEncode}</span> <tpl if="key"><span style="font-style: italic; font-size: small;">({key:htmlEncode})</span></tpl></div></tpl>')
    });
    Babel.combo.Context.superclass.constructor.call(this, config);
};
Ext.extend(Babel.combo.Context, MODx.combo.Context, {
    setValue: function (value) {
        if (value === '') {
            value = null;
        }
        Babel.combo.Context.superclass.setValue.call(this, value);
    }
});
Ext.reg('babel-combo-context', Babel.combo.Context);
