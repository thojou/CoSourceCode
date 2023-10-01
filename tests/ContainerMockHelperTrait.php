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

use ilCoSourceCodePlugin;
use ILIAS\DI\Container;

trait ContainerMockHelperTrait
{
    /**
     * @var array<string, object>
     */
    private array $mockedServices = [];

    public function mockCoreService(string $key, object $service): ContainerMockHelperInterface
    {
        global $DIC;

        if(!$DIC instanceof Container) {
            $DIC = $this->getMockBuilder(Container::class)
                ->onlyMethods(['offsetExists', 'offsetGet'])
                ->disableOriginalConstructor()
                ->getMock();

            $DIC->method('offsetExists')
                ->willReturnCallback(function (string $key) {
                    return isset($this->mockedServices[$key]);
                });

            $DIC->method('offsetGet')
                ->willReturnCallback(function (string $key) {
                    return $this->mockedServices[$key];
                });
        }

        $this->mockedServices[$key] = $service;

        return $this;
    }

    public function mockPluginService(string $key, object $service): ContainerMockHelperInterface
    {
        return $this->mockCoreService(ilCoSourceCodePlugin::PLUGIN_ID . '.' . $key, $service);
    }

    public function getDICMock(): Container
    {
        global $DIC;

        return $DIC;
    }

}
