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

use CoSourceCode\CodeHighlighter;
use CoSourceCode\DI\PluginContainer;
use CoSourceCode\Options\LanguageOptionsService;
use CoSourceCode\Options\ThemeOptionsService;
use CoSourceCode\Tests\AbstractGUITest;
use CoSourceCode\Tests\PageComponentHelperTrait;
use ilCoSourceCodePlugin;
use ilCoSourceCodePluginGUI;
use ilSetting;

class CoSourceCodePluginGUITest extends AbstractGUITest
{
    use PageComponentHelperTrait;

    protected function setUp(): void
    {
        $this->setupGUICommons();
        ;
        $this->registerPCPluggedGUI();

        $ilSettings = $this->createMock(ilSetting::class);
        $DIC = $this
            ->mockPluginService(LanguageOptionsService::class, new LanguageOptionsService($ilSettings))
            ->mockPluginService(ThemeOptionsService::class, new ThemeOptionsService($ilSettings))
            ->mockPluginService(CodeHighlighter::class, new CodeHighlighter())
            ->getDICMock();
        PluginContainer::init($DIC, ilCoSourceCodePlugin::PLUGIN_ID);
    }

    public function testInsert(): void
    {
        $this->mockCommand('insert');

        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testEdit(): void
    {
        $this->mockCommand('edit');

        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testCreateInvalid(): void
    {
        $this->mockCommand('create');
        $this->mockPostRequest([]);

        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testCreateFailed(): void
    {
        $this->mockCommand('create');
        $this->mockPostRequest([
            'language' => 'js',
            'lineNumbers' => true,
            'theme' => 'atom-one-light',
            'srcCode' => 'console.log("Hello World");',
            'description' => 'Print "Hello World" to console'
        ]);

        $this->expectPCGUICreate($this->anything(), false);
        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testCreate(): void
    {
        $properties = [
            'language' => 'js',
            'lineNumbers' => true,
            'theme' => 'atom-one-light',
            'srcCode' => 'console.log("Hello World");',
            'description' => 'Print "Hello World" to console'
        ];

        $this->mockCommand('create');
        $this->mockPostRequest($properties);

        $this->expectPCGUICreate($this->equalTo($properties));
        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testUpdateInvalid(): void
    {
        $this->mockCommand('update');
        $this->mockPostRequest([]);

        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }


    public function testUpdateFailed(): void
    {
        $this->mockCommand('update');
        $this->mockPostRequest([
            'language' => 'js',
            'lineNumbers' => true,
            'theme' => 'atom-one-light',
            'srcCode' => 'console.log("Hello World");',
            'description' => 'Print "Hello World" to console'
        ]);

        $this->expectPCGUIUpdate($this->anything(), false);
        $this->expectTplContent($this->logicalAnd(
            $this->stringContains('language'),
            $this->stringContains('theme'),
            $this->stringContains('lineNumbers'),
            $this->stringContains('srcCode'),
            $this->stringContains('description'),
        ));

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testUpdate(): void
    {
        $properties = [
            'language' => 'js',
            'lineNumbers' => false,
            'theme' => 'atom-one-light',
            'srcCode' => 'console.log("Hello World");',
            'description' => 'Print "Hello World" to console'
        ];

        $this->mockCommand('update');
        $this->mockPostRequest($properties);

        $this->expectPCGUIUpdate($this->equalTo($properties));
        $this->expectTplContent(
            $this->logicalAnd(
                $this->stringContains('language'),
                $this->stringContains('theme'),
                $this->stringContains('lineNumbers'),
                $this->stringContains('srcCode'),
                $this->stringContains('description'),
            )
        );

        $this->executePageComponentGUI(new ilCoSourceCodePluginGUI(), ilCoSourceCodePlugin::class);
    }

    public function testGetElementHtmlWithoutLineNumbers(): void
    {
        $plugin = $this->createMock(ilCoSourceCodePlugin::class);
        $plugin->method('getDirectory')->willReturn('Customizing/global/plugins/Services/COPage/PageComponent/CoSourceCode');

        $gui = new ilCoSourceCodePluginGUI();
        $gui->setPlugin($plugin);
        $html = $gui->getElementHTML("any", [
            'language' => 'js',
            'theme' => 'atom-one-light',
            'lineNumbers' => false,
            'srcCode' => 'console.log("Hello World");',
            'description' => 'Print "Hello World" to console'
        ], "1");

        $this->assertStringContainsString('<pre class="co-source-code"><code class="hljs javascript">', $html);
        $this->assertStringContainsString('console', $html);
        $this->assertStringContainsString('"Hello World"', $html);
        $this->assertStringContainsString('<em class="small">Print "Hello World" to console</em>', $html);
    }

    public function testGetElementHtmlWithLineNumbers(): void
    {
        $plugin = $this->createMock(ilCoSourceCodePlugin::class);
        $plugin->method('getDirectory')->willReturn('Customizing/global/plugins/Services/COPage/PageComponent/CoSourceCode');

        $gui = new ilCoSourceCodePluginGUI();
        $gui->setPlugin($plugin);
        $html = $gui->getElementHTML("any", [
            'language' => 'js',
            'theme' => 'atom-one-light',
            'lineNumbers' => true,
            'srcCode' => 'console.log("Hello World");',
            'description' => 'Print "Hello World" to console'
        ], "1");

        $this->assertStringContainsString('<table class="hljs">', $html);
        $this->assertStringContainsString('<pre><code class="hljs javascript">', $html);
        $this->assertStringContainsString('data-line-number', $html);
        $this->assertStringContainsString('console', $html);
        $this->assertStringContainsString('"Hello World"', $html);
        $this->assertStringContainsString('<em class="small">Print "Hello World" to console</em>', $html);
    }
}
