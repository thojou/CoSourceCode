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

use CoSourceCode\DI\PluginContainer;
use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\ThemeOptionsService;
use CoSourceCode\Tests\IliasContainerMockHelperInterface;
use CoSourceCode\Tests\IliasContainerMockHelperTrait;
use ilComponentFactory;
use ilCoSourceCodeConfigGUI;
use ilCoSourceCodePlugin;
use ilCtrl;
use ilGlobalTemplate;
use ilLanguage;
use PHPUnit\Framework\TestCase;

class CoSourceCodeConfigGUITest extends TestCase implements IliasContainerMockHelperInterface
{
    use IliasContainerMockHelperTrait;

    public function testConfigure(): void
    {
        $tpl = $this->createMock(ilGlobalTemplate::class);
        $DIC = $this
            ->mockCoreService('tpl', $tpl)
            ->mockCoreService('lng', $this->createMock(ilLanguage::class))
            ->mockCoreService('ilCtrl', $this->createMock(ilCtrl::class))
            ->mockCoreService('component.factory', $this->createMock(ilComponentFactory::class))
            ->mockPluginService(LanguageOptionsService::class, $this->createMock(LanguageOptionsService::class))
            ->mockPluginService(ThemeOptionsService::class, $this->createMock(ThemeOptionsService::class))
            ->getDICMock();

        PluginContainer::init($DIC, ilCoSourceCodePlugin::PLUGIN_ID);

        $tpl
            ->expects($this->once())
            ->method('setContent')
            ->with($this->logicalAnd(
                $this->stringContains('language_actives'),
                $this->stringContains('language_default'),
                $this->stringContains('theme_actives'),
                $this->stringContains('theme_default'),
            ));

        $gui = new ilCoSourceCodeConfigGUI();
        $gui->setPluginObject($this->createMock(ilCoSourceCodePlugin::class));
        $gui->performCommand('configure');
    }
}
