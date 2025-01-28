<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace mikrobi\Babel\Processors\Context\Setting\V2;

require_once MODX_CORE_PATH . 'model/modx/processors/context/setting/create.class.php';

class Create extends \modContextSettingCreateProcessor
{
    use \mikrobi\Babel\Processors\Context\Setting\CommonTrait;
    
    public function process() 
    {
        $resultProcess = parent::process();
        $this->runOnSaveEvent($this, $this->modeNew);
        
        return $resultProcess;
    }
}
