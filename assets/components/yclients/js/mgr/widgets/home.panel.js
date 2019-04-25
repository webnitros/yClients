yClients.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'yclients-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('yclients') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('yclients_items'),
                layout: 'anchor',
                items: [{
                    html: _('yclients_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'yclients-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    yClients.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(yClients.panel.Home, MODx.Panel);
Ext.reg('yclients-panel-home', yClients.panel.Home);
