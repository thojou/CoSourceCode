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

use ilComponentRepositoryWrite;
use PHPUnit\Framework\MockObject\MockObject;

trait PluginHelperTrait
{
    /**
     * @var ilComponentRepositoryWrite&MockObject
     */
    protected ilComponentRepositoryWrite $componentRepository;

    public function registerComponentRepository(string $pluginId)
    {
        $this->componentRepository = $this->createMock(ilComponentRepositoryWrite::class);

        $this->componentRepository
            ->method('hasPluginId')
            ->with($this->equalTo($pluginId))
            ->willReturn(true);

        return $this->componentRepository;
    }
}
