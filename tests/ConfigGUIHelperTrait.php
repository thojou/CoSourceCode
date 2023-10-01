<?php

declare(strict_types=1);

/*
 * This file is part of the CoSourceCode Plugin for ILIAS.
 *
 * (c) Thomas Joußen <tjoussen@databay.de>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoSourceCode\Tests;

use ilPluginConfigGUI;

trait ConfigGUIHelperTrait
{
    public function performConfigGUICommand(string $command, ilPluginConfigGUI $gui, string $pluginClass)
    {
        $gui->setPluginObject($this->createMock($pluginClass));
        $gui->performCommand($command);
    }
}
