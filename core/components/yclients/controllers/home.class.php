<?php

/**
 * The home manager controller for yClients.
 *
 */
class yClientsHomeManagerController extends modExtraManagerController
{
    /** @var yClients $yClients */
    public $yClients;


    /**
     *
     */
    public function initialize()
    {
        $this->yClients = $this->modx->getService('yClients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['yclients:manager','yclients:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('yclients');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->yClients->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/yclients.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/widgets/items/grid.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/widgets/items/windows.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->yClients->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        yClients.config = ' . json_encode($this->yClients->config) . ';
        yClients.config.connector_url = "' . $this->yClients->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "yclients-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="yclients-panel-home-div"></div>';

        return '';
    }
}