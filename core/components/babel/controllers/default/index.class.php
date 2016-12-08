<?php

require_once dirname(dirname(dirname(__FILE__))).'/model/babel/babel.class.php';

class BabelIndexManagerController extends modExtraManagerController
{

    /** @var Babel $babel */
    public $babel;

    public function initialize()
    {
        $this->babel = new Babel($this->modx);
        $this->addCss($this->babel->config['cssUrl'].'babel.css');
        $this->addCss($this->babel->config['cssUrl'].'cmp.css');
        $this->addJavascript($this->babel->config['jsUrl'].'mgr/babel.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Babel.config = '.$this->modx->toJSON($this->babel->config).';
        });
        </script>');
        return parent::initialize();
    }

    public function getLanguageTopics()
    {
        return array('babel:default', 'babel:cmp');
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->babel->config['jsUrl'].'mgr/widgets/combo.context.js');
        $this->addCss($this->babel->config['jsUrl'].'ux/LockingGridView/LockingGridView.css');
        $this->addJavascript($this->babel->config['jsUrl'].'ux/LockingGridView/LockingGridView.js');
        $this->addJavascript($this->babel->config['jsUrl'].'mgr/widgets/grid.resourcematrix.js');
        $this->addJavascript($this->babel->config['jsUrl'].'mgr/widgets/panel.home.js');
        $this->addLastJavascript($this->babel->config['jsUrl'].'mgr/sections/index.js');
    }

    public function process(array $scriptProperties = array())
    {

    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('babel');
    }

    public function getTemplateFile()
    {
        return 'index.tpl';
    }

}
