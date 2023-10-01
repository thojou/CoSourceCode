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

namespace CoSourceCode\Tests\DI;

use CoSourceCode\DI\PluginContainer;
use ilCoSourceCodePlugin;
use ILIAS\DI\Container;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PluginContainerTest extends TestCase
{
    /**
     * @return void
     * @runInSeparateProcess needed because of static instance
     */
    public function testGetWithoutInit(): void
    {
        $this->expectException(RuntimeException::class);
        PluginContainer::get();
    }

    public function testUnknownService(): void
    {
        $this->expectException(RuntimeException::class);

        PluginContainer::init($this->createMock(Container::class), ilCoSourceCodePlugin::PLUGIN_ID);
        PluginContainer::get()->getService('unkownService');
    }
}
