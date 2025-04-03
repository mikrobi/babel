<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;
use xPDO;

class OnDocFormPrerender extends Plugin
{
    /**
     * Check if the context of the current resource is referenced in babel.contextKeys
     * @return bool
     */
    public function init()
    {
        $resource = &$this->scriptProperties['resource'];
        if (!$resource || !in_array($resource->get('context_key'), $this->babel->getOption('contexts'))) {
            return false;
        }

        return parent::init();
    }

    /**
     * Add the Babel box to the resource form
     * @return void
     */
    public function process()
    {
        $assetsUrl = $this->babel->getOption('assetsUrl');
        $jsUrl = $this->babel->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->babel->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        $resource = &$this->scriptProperties['resource'];
        $linkedResources = $this->babel->getLinkedResources($resource->get('id'));
        if (empty($linkedResources)) {
            // Always be sure that the Babel TV is set
            $this->babel->initBabelTv($resource);
        }

        $this->babel->setOption('context_key', $resource->get('context_key'));
        $this->babel->setOption('menu', $this->babel->getMenu($resource));
        if (empty($this->babel->getOption('menu'))) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, '[Babel] Could not load menu for context key: "' . $this->babel->getOption('context_key') . '". Try to check "babel.contextKeys" in System Settings. If this is intended, you can ignore this warning.');
            return;
        }

        $this->modx->controller->addLexiconTopic('babel:default');
        if ($this->babel->getOption('debug') && ($this->babel->getOption('assetsUrl') != MODX_ASSETS_URL . 'components/babel/')) {
            $this->modx->controller->addCss($cssSourceUrl . 'babel.css?v=v' . $this->babel->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'babel.js?v=v' . $this->babel->version);
        } else {
            $this->modx->controller->addCss($cssUrl . 'resourcebutton.min.css?v=v' . $this->babel->version);
            $this->modx->controller->addJavascript($jsUrl . 'resourcebutton.min.js?v=v' . $this->babel->version);
        }
        $this->modx->controller->addHtml('<script type="text/javascript">
        Ext.onReady(function () {
            Babel.config = ' . json_encode($this->babel->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . ';
            Babel.getMenu(Babel.config.menu);
        });
        </script>');
    }
}
