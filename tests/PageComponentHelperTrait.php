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

use ilPageComponentPlugin;
use ilPageComponentPluginGUI;
use ilPCPlugged;
use ilPCPluggedGUI;
use PHPUnit\Framework\MockObject\MockObject;

trait PageComponentHelperTrait
{
    /**
     * @var ilPCPluggedGUI&MockObject
     */
    protected ilPCPluggedGUI $pcGUI;

    /**
     * @param  array                     $properties
     * @return ilPCPluggedGUI&MockObject
     */
    public function registerPCPluggedGUI(array $properties = []): ilPCPluggedGUI
    {
        $this->pcGUI = $this->createMock(ilPCPluggedGUI::class);

        $pageContent = $this->createMock(ilPCPlugged::class);
        $pageContent
            ->method('getProperties')
            ->willReturn($properties);

        $this->pcGUI
            ->method('getContentObject')
            ->willReturn($pageContent);

        return $this->pcGUI;
    }

    public function expectPCGUICreate($expected, bool $willSucceed = true): void
    {
        $this->expectPCGUICommand('createElement', $expected, $willSucceed);
    }

    public function expectPCGUIUpdate($expected, bool $willSucceed = true): void
    {
        $this->expectPCGUICommand('updateElement', $expected, $willSucceed);
    }

    /**
     * @param ilPageComponentPluginGUI            $gui
     * @param class-string<ilPageComponentPlugin> $pluginClass
     *
     * @return void
     */
    public function executePageComponentGUI(ilPageComponentPluginGUI $gui, string $pluginClass): void
    {
        $gui->setPlugin($this->createMock($pluginClass));
        $gui->setPCGUI($this->pcGUI);
        $gui->executeCommand();
    }

    private function expectPCGUICommand(string $command, $expected, bool $willSucceed = true): void
    {
        $this->pcGUI->expects($this->once())->method($command)->with($expected)->willReturn($willSucceed);
    }
}
