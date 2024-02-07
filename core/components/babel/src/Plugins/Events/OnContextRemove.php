<?php
/**
 * @package babel
 * @subpackage plugin
 */

namespace mikrobi\Babel\Plugins\Events;

use mikrobi\Babel\Plugins\Plugin;

class OnContextRemove extends Plugin
{
    /**
     * Remove language links to the removed context
     * @return void
     */
    public function process()
    {
        $context = &$this->scriptProperties['context'];
        if ($context) {
            $this->babel->removeLanguageLinksToContext($context->get('key'));
        }
    }
}
