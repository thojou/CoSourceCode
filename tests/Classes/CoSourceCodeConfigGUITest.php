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

use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\ThemeOptionsService;
use ilCoSourceCodeConfigGUI;
use ilCoSourceCodePlugin;
use ilSetting;
use LogicException;
use Thojou\Ilias\Plugin\Utils\DI\PluginContainer;
use Thojou\Ilias\Plugin\Utils\Test\TestCase\AbstractGUITestCase;
use Thojou\Ilias\Plugin\Utils\Test\Traits\ConfigGUIHelperTrait;

class CoSourceCodeConfigGUITest extends AbstractGUITestCase
{
    use ConfigGUIHelperTrait;

    protected function setUp(): void
    {
        $this->setupGUICommons();

        $ilSettings = $this->createMock(ilSetting::class);
        $DIC = $this
            ->mockPluginService(LanguageOptionsService::class, new LanguageOptionsService($ilSettings))
            ->mockPluginService(ThemeOptionsService::class, new ThemeOptionsService($ilSettings))
            ->getDICMock();

        PluginContainer::init($DIC, ilCoSourceCodePlugin::PLUGIN_ID);
    }

    public function testMissingPluginObject(): void
    {
        $this->expectException(LogicException::class);

        $gui = new ilCoSourceCodeConfigGUI();
        $gui->performCommand('configure');
    }

    public function testConfigure(): void
    {
        $this->mockGetRequest();

        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language_actives'),
            $this->stringContains('language_default'),
            $this->stringContains('theme_actives'),
            $this->stringContains('theme_default'),
        ));

        $this->performConfigGUICommand('configure', new ilCoSourceCodeConfigGUI(), ilCoSourceCodePlugin::class);
    }

    public function testInvalidSave(): void
    {
        $this->mockPostRequest([]);
        $this->expectRedirect($this->never(), 'configure');
        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language_actives'),
            $this->stringContains('language_default'),
            $this->stringContains('theme_actives'),
            $this->stringContains('theme_default'),
        ));
        $this->performConfigGUICommand('save', new ilCoSourceCodeConfigGUI(), ilCoSourceCodePlugin::class);
    }

    public function testSave(): void
    {
        $this->mockPostRequest([
            'language_actives' => ['php', 'js'],
            'language_default' => 'js',
            'theme_actives' => ['atom-one-dark', 'atom-one-light'],
            'theme_default' => 'atom-one-light',
        ]);

        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language_actives'),
            $this->stringContains('language_default'),
            $this->stringContains('theme_actives'),
            $this->stringContains('theme_default'),
        ));

        $this->performConfigGUICommand('save', new ilCoSourceCodeConfigGUI(), ilCoSourceCodePlugin::class);
    }

    protected function getPluginId(): string
    {
        return ilCoSourceCodePlugin::PLUGIN_ID;
    }
}
