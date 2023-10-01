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

namespace CoSourceCode\Tests\Classes;

use CoSourceCode\Tests\AbstractGUITest;
use CoSourceCode\Tests\PluginHelperTrait;
use ilCoSourceCodePlugin;

class CoSourceCodePluginTest extends AbstractGUITest
{
    use PluginHelperTrait;

    public function testInitializationOnce()
    {
        $this->registerLanguage();
        $database = $this->registerDatabase();
        $componentRepository = $this->registerComponentRepository(ilCoSourceCodePlugin::PLUGIN_ID);
        new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);
        new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);

        $this->assertTrue(true);
    }

    public function testGetPluginDir()
    {
        $this->assertEquals(
            "Customizing/global/plugins/Services/COPage/PageComponent/CoSourceCode",
            ilCoSourceCodePlugin::getPluginDir()
        );
    }

    public function testIsValidParentType()
    {
        $this->registerLanguage();
        $database = $this->registerDatabase();
        $componentRepository = $this->registerComponentRepository(ilCoSourceCodePlugin::PLUGIN_ID);
        $plugin = new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);

        $this->assertTrue($plugin->isValidParentType("always"));
    }


    public function testCssFiles()
    {
        $this->registerLanguage();
        $database = $this->registerDatabase();
        $componentRepository = $this->registerComponentRepository(ilCoSourceCodePlugin::PLUGIN_ID);
        $plugin = new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);

        $this->assertEquals(
            [
                'assets/css/source-code.css'
            ],
            $plugin->getCssFiles('any')
        );
    }

}
