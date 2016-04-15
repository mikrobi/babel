var Babel = function (config) {
    config = config || {};
    Babel.superclass.constructor.call(this, config);
};
Ext.extend(Babel, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}
});
Ext.reg('babel', Babel);
Babel = new Babel();