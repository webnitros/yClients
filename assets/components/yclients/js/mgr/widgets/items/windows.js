yClients.window.CreateItem = function (config) {
    config = config || {}
    config.url = yClients.config.connector_url

    Ext.applyIf(config, {
        title: _('yclients_color_create'),
        width: 600,
        cls: 'yclients_windows',
        baseParams: {
            action: 'mgr/item/create',
            resource_id: config.resource_id
        }
    })
    yClients.window.CreateItem.superclass.constructor.call(this, config)
}
Ext.extend(yClients.window.CreateItem, yClients.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                fieldLabel: _('yclients_item_name'),
                name: 'item',
                id: config.id + '-item',
                anchor: '99%',
                allowBlank: false,
            }, {
                xtype: 'textarea',
                fieldLabel: _('yclients_item_description'),
                name: 'description',
                id: config.id + '-description',
                height: 150,
                anchor: '99%'
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('yclients_item_active'),
                name: 'active',
                id: config.id + '-active',
                checked: true,
            }
        ]


    }
})
Ext.reg('yclients-item-window-create', yClients.window.CreateItem)

yClients.window.UpdateItem = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('yclients_color_update'),
        baseParams: {
            action: 'mgr/item/update',
            resource_id: config.resource_id
        },
    })
    yClients.window.UpdateItem.superclass.constructor.call(this, config)

}
Ext.extend(yClients.window.UpdateItem, yClients.window.CreateItem)
Ext.reg('yclients-item-window-update', yClients.window.UpdateItem)