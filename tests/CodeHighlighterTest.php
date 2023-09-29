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

use CoSourceCode\CodeHighlighter;
use PHPUnit\Framework\TestCase;

class CodeHighlighterTest extends TestCase
{
    public function testInstance(): void
    {
        $highlighter = new CodeHighlighter();

        $this->assertInstanceOf(CodeHighlighter::class, $highlighter);
    }

}
