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

use ilCoSourceCodePlugin;
use Thojou\Ilias\Plugin\Utils\Test\TestCase\AbstractPluginTestCase;

class CoSourceCodePluginTest extends AbstractPluginTestCase
{
    public function testInitializationOnce(): void
    {
        $this->registerLanguage();
        $database = $this->registerDatabase();
        $componentRepository = $this->registerComponentRepository(ilCoSourceCodePlugin::PLUGIN_ID);
        new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);
        new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);

        $this->assertTrue(true);
    }

    public function testGetPluginDir(): void
    {
        $this->assertEquals(
            "Customizing/global/plugins/Services/COPage/PageComponent/CoSourceCode",
            ilCoSourceCodePlugin::getPluginDir()
        );
    }

    public function testIsValidParentType(): void
    {
        $this->registerLanguage();
        $database = $this->registerDatabase();
        $componentRepository = $this->registerComponentRepository(ilCoSourceCodePlugin::PLUGIN_ID);
        $plugin = new ilCoSourceCodePlugin($database, $componentRepository, ilCoSourceCodePlugin::PLUGIN_ID);

        $this->assertTrue($plugin->isValidParentType("always"));
    }


    public function testCssFiles(): void
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

    protected function getPluginId(): string
    {
        return ilCoSourceCodePlugin::PLUGIN_ID;
    }
}
