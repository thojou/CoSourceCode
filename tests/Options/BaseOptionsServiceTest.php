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

use CoSourceCode\Options\BaseOptionsService;
use PHPUnit\Framework\TestCase;

class BaseOptionsServiceTest extends TestCase
{
    public function testInvalidConfiguredActives(): void
    {
        $settingsMock = $this->createMock(\ilSetting::class);
        $settingsMock
            ->method('get')
            ->willReturn('invalid');

        $service = $this->getMockBuilder(BaseOptionsService::class)
            ->setConstructorArgs([$settingsMock])
            ->getMockForAbstractClass();

        $service
            ->expects($this->once())
            ->method('getDefaultActives')
            ->willReturn(['a', 'b', 'c']);

        $this->assertEquals(
            ['a', 'b', 'c'],
            $service->loadActives(),
        );
    }

}
