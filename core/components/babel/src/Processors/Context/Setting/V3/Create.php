<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace mikrobi\Babel\Processors\Context\Setting\V3;

use MODX\Revolution\modSystemEvent;


class Create extends \MODX\Revolution\Processors\Context\Setting\Create
{
    use \mikrobi\Babel\Processors\Context\Setting\CommonTrait;
    
    public function process() 
    {
        $resultProcess = parent::process();
        $this->runOnSaveEvent($this, modSystemEvent::MODE_NEW);
        
        return $resultProcess;
    }
}
