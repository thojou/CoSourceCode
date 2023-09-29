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

namespace CoSourceCode\Tests\Options;

use CoSourceCode\Options\ThemeOptionsService;
use PHPUnit\Framework\TestCase;

class ThemeOptionsServiceTest extends TestCase
{
    public function testLoadAll(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $service = new ThemeOptionsService($settingMock);
        $this->assertGreaterThan(0, count($service->loadAll()));
    }

    public function testLoadActivesNotConfigured(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $settingMock->method('get')
            ->willReturn(null);

        $service = new ThemeOptionsService($settingMock);
        $this->assertEquals(
            [
                'atom-one-dark',
                'atom-one-light'
            ],
            $service->loadActives()
        );
    }

    public function testSetActives(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $settingMock
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('theme'),
                $this->equalTo('["atom-one-dark"]')
            );

        $settingMock
            ->expects($this->once())
            ->method('get')
            ->willReturn('["atom-one-dark"]');

        $service = new ThemeOptionsService($settingMock);
        $service->setActives(['atom-one-dark']);
        $this->assertEquals(
            [
                'atom-one-dark'
            ],
            $service->loadActives()
        );
    }

    public function testGetDefaultNotConfigured(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $settingMock
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $service = new ThemeOptionsService($settingMock);
        $this->assertEquals(
            'atom-one-dark',
            $service->getDefault()
        );
    }

    public function testSetDefault(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $settingMock
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('theme_default'),
                $this->equalTo('atom-one-light')
            );

        $settingMock
            ->expects($this->once())
            ->method('get')
            ->willReturn('atom-one-light');

        $service = new ThemeOptionsService($settingMock);
        $service->setDefault('atom-one-light');
        $this->assertEquals(
            'atom-one-light',
            $service->getDefault()
        );
    }

}
