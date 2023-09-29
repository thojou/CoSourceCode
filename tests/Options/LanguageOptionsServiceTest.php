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

use CoSourceCode\Options\LanguageOptionsService;
use PHPUnit\Framework\TestCase;

class LanguageOptionsServiceTest extends TestCase
{
    public function testLoadAll(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $service = new LanguageOptionsService($settingMock);
        $this->assertGreaterThan(0, count($service->loadAll()));
    }

    public function testLoadActivesNotConfigured(): void
    {
        $settingMock = $this->createMock(\ilSetting::class);

        $settingMock->method('get')
            ->willReturn(null);

        $service = new LanguageOptionsService($settingMock);
        $this->assertEquals(
            [
                'bash',
                'c',
                'c++',
                'css',
                'go',
                'html',
                'js',
                'java',
                'php',
                'python',
                'ruby',
                'vb',
                'xml'
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
                $this->equalTo('language'),
                $this->equalTo('["php","js"]')
            );

        $settingMock
            ->expects($this->once())
            ->method('get')
            ->willReturn('["php","js"]');

        $service = new LanguageOptionsService($settingMock);
        $service->setActives(['php', 'js']);
        $this->assertEquals(
            [
                'php',
                'js'
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

        $service = new LanguageOptionsService($settingMock);
        $this->assertEquals(
            'php',
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
                $this->equalTo('language_default'),
                $this->equalTo('js')
            );

        $settingMock
            ->expects($this->once())
            ->method('get')
            ->willReturn('js');

        $service = new LanguageOptionsService($settingMock);
        $service->setDefault('js');
        $this->assertEquals(
            'js',
            $service->getDefault()
        );
    }
}
