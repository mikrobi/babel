<?php
/**
 * Index controller
 *
 * @package babel
 * @subpackage controllers
 */

/**
 * Class BabelIndexManagerController
 */
class BabelIndexManagerController extends modExtraManagerController
{
    /** @var Babel $babel */
    public $babel;

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $corePath = $this->modx->getOption('babel.core_path', null, $this->modx->getOption('core_path') . 'components/babel/');
        $this->babel = $this->modx->getService('babel', Babel::class, $corePath . 'model/babel/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function loadCustomCssJs()
    {
        $assetsUrl = $this->babel->getOption('assetsUrl');
        $jsUrl = $this->babel->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->babel->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        if ($this->babel->getOption('debug') && ($this->babel->getOption('assetsUrl') != MODX_ASSETS_URL . 'components/babel/')) {
            $this->addCss($cssSourceUrl . 'babel.css?v=v' . $this->babel->version);
            $this->addJavascript($jsSourceUrl . 'babel.js?v=v' . $this->babel->version);
            $this->addJavascript($jsSourceUrl . 'helper/combo.js?v=v' . $this->babel->version);
            $this->addJavascript($jsSourceUrl . '../ux/LockingGridView/LockingGridView.js?v=v' . $this->babel->version);
            $this->addJavascript($jsSourceUrl . 'widgets/resourcematrix.grid.js?v=v' . $this->babel->version);
            $this->addJavascript($jsSourceUrl . 'widgets/home.panel.js?v=v' . $this->babel->version);
            $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.grid.settings.js');
            $this->addJavascript($jsSourceUrl . 'widgets/settings.panel.js?v=v' . $this->babel->version);
            $this->addLastJavascript($jsSourceUrl . 'sections/index.js?v=v' . $this->babel->version);
        } else {
            $this->addCss($cssUrl . 'babel.min.css?v=v' . $this->babel->version);
            $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.grid.settings.js');
            $this->addJavascript($jsUrl . 'babel.min.js?v=v' . $this->babel->version);
        }

        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Babel.config = ' . json_encode($this->babel->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . ';
            MODx.load({xtype: "babel-page-home"});
        });
        </script>');
    }

    /**
     * {@inheritDoc}
     * @return string[]
     */
    public function getLanguageTopics()
    {
        return ['core:setting', 'babel:default'];
    }

    /**
     * {@inheritDoc}
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = [])
    {
    }

    /**
     * {@inheritDoc}
     * @return string|null
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('babel');
    }

    /**
     * {@inheritDoc}
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->babel->getOption('templatesPath') . 'index.tpl';
    }

}
