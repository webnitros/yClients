var yClients = function (config) {
    config = config || {};
    yClients.superclass.constructor.call(this, config);
};
Ext.extend(yClients, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('yclients', yClients);

yClients = new yClients();