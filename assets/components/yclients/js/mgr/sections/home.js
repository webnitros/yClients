yClients.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'yclients-panel-home',
            renderTo: 'yclients-panel-home-div'
        }]
    });
    yClients.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(yClients.page.Home, MODx.Component);
Ext.reg('yclients-page-home', yClients.page.Home);